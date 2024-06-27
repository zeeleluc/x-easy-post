<?php
namespace App\Service;

class BasePuzzle
{

    protected string $backgroundColor;

    protected string $textColor;

    protected string $text = '';

    private array $reservedCoordinates = [];

    public static function make(): BasePuzzle
    {
        return new self();
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    private function isReservedCoordinate(int $x, int $y):? string
    {
        foreach ($this->reservedCoordinates as $reservedCoordinate) {
            if ($reservedCoordinate['x'] === $x && $reservedCoordinate['y'] === $y) {
                return $reservedCoordinate['letter'];
            }
        }

        return null;
    }

    /**
     * @throws \ImagickDrawException
     * @throws \ImagickException
     */
    public function render(): array
    {
        $filename = uniqid() . '.png';
        $urlTMP = ROOT . '/tmp/' . $filename;

        $image = new \Imagick();
        $image->newImage(1100, 1100, $this->backgroundColor);
        $image->setImageFormat("png");

        if ($this->text) {
            $words = explode(' ', $this->text);
            foreach ($words as $word) {
                $horizontal = rarity_change();
                if ($horizontal) {
                    $wordLenght = strlen($word);
                    $minStartX = 25 - $wordLenght;
                    $rowX = rand(1, $minStartX);
                    $rowY = rand(1, 25);
                    foreach (str_split($word) as $letter) {
                        $this->reservedCoordinates[] = [
                            'x' => $rowX,
                            'y' => $rowY,
                            'letter' => $letter,
                        ];
                        $rowX++;
                    }
                } else {
                    $wordLenght = strlen($word);
                    $minStartY = 25 - $wordLenght;
                    $rowY= rand(1, $minStartY);
                    $rowX = rand(1, 25);
                    foreach (str_split($word) as $letter) {
                        $this->reservedCoordinates[] = [
                            'x' => $rowX,
                            'y' => $rowY,
                            'letter' => $letter,
                        ];
                        $rowY++;
                    }
                }
            }
        }

        for ($ix = 1; $ix <= 25; $ix++) {
            for ($iy = 1; $iy <= 25; $iy++) {
                $reservedCoordinateLetter = $this->isReservedCoordinate($ix, $iy);
                if ($reservedCoordinateLetter) {
                    $image->drawImage($this->addLetter($reservedCoordinateLetter, $ix, $iy, $this->textColor));
                } else {
                    $letter = $this->getRandomLetter();
                    $color = adjust_brightness($this->textColor, 0.5);
                    $image->drawImage($this->addLetter($letter, $ix, $iy, $color));
                }
            }
        }

        $image->writeImage($urlTMP);

        return [
            'urlCDN' => $this->upload($urlTMP, $filename),
            'urlTMP' => $urlTMP,
        ];
    }

    private function upload(string $urlTMP, string $filename): string
    {
        return (new Bucket())->uploadFile($urlTMP, $filename);
    }

    /**
     * @throws \ImagickException
     * @throws \ImagickDrawException
     */
    private function addLetter(string $letter, int $x, int $y, string $color): \ImagickDraw
    {
        $letter = ucwords($letter);

        $draw = new \ImagickDraw();
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->setFont(ROOT . "/assets/fonts/bm-hanna-webfont.ttf");
        $draw->setFontSize(25);
        $draw->setFillColor(new \ImagickPixel($color));
        $draw->annotation(($x * 40) + 30, ($y * 40) + 30, $letter);

        return $draw;
    }

    private function getRandomLetter(): string
    {
        $letters = range('A', 'Z');
        shuffle($letters);
        $letter = $letters[0];

        if (in_array($letter, ['Q', 'X', 'Y', 'Z'])) {
            if (rarity_change(5)) {
                return $letter;
            } else {
                return $this->getRandomLetter();
            }
        }

        return $letter;
    }
}
