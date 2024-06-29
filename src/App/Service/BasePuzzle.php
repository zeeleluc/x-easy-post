<?php

namespace App\Service;

class BasePuzzle
{
    protected string $backgroundColor = '#ffffff';
    protected string $textColor = '#000000';
    protected string $textColorFaded = '#aaaaaa';
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

    private function isReservedCoordinate(int $x, int $y): ?string
    {
        foreach ($this->reservedCoordinates as $reservedCoordinate) {
            if ($reservedCoordinate['x'] === $x && $reservedCoordinate['y'] === $y) {
                return $reservedCoordinate['letter'];
            }
        }
        return null;
    }

    private function canPlaceHorizontally(int $startX, int $startY, string $word): bool
    {
        $wordLength = strlen($word);
        if ($startX + $wordLength > 26 || $startY > 25) {
            return false;
        }
        for ($i = 0; $i < $wordLength; $i++) {
            $x = $startX + $i;
            $y = $startY;
            if ($this->isReservedCoordinate($x, $y) && $this->isReservedCoordinate($x, $y) !== $word[$i]) {
                return false;
            }
            if ($this->isReservedCoordinate($x, $y - 1) || $this->isReservedCoordinate($x, $y + 1)) {
                return false;
            }
        }
        if ($this->isReservedCoordinate($startX - 1, $startY) || $this->isReservedCoordinate($startX + $wordLength, $startY)) {
            return false;
        }
        return true;
    }

    private function canPlaceVertically(int $startX, int $startY, string $word): bool
    {
        $wordLength = strlen($word);
        if ($startY + $wordLength > 26 || $startX > 25) {
            return false;
        }
        for ($i = 0; $i < $wordLength; $i++) {
            $x = $startX;
            $y = $startY + $i;
            if ($this->isReservedCoordinate($x, $y) && $this->isReservedCoordinate($x, $y) !== $word[$i]) {
                return false;
            }
            if ($this->isReservedCoordinate($x - 1, $y) || $this->isReservedCoordinate($x + 1, $y)) {
                return false;
            }
        }
        if ($this->isReservedCoordinate($startX, $startY - 1) || $this->isReservedCoordinate($startX, $startY + $wordLength)) {
            return false;
        }
        return true;
    }

    private function findHorizontalPosition(string $word): ?array
    {
        $wordLength = strlen($word);
        $maxStartX = 26 - $wordLength;

        for ($attempt = 0; $attempt < 100; $attempt++) {
            $x = random_int(1, $maxStartX);
            $y = random_int(1, 25);
            if ($this->canPlaceHorizontally($x, $y, $word)) {
                return ['x' => $x, 'y' => $y, 'direction' => 'horizontal'];
            }
        }
        return null;
    }

    private function findVerticalPosition(string $word): ?array
    {
        $wordLength = strlen($word);
        $maxStartY = 26 - $wordLength;

        for ($attempt = 0; $attempt < 100; $attempt++) {
            $x = random_int(1, 25);
            $y = random_int(1, $maxStartY);
            if ($this->canPlaceVertically($x, $y, $word)) {
                return ['x' => $x, 'y' => $y, 'direction' => 'vertical'];
            }
        }
        return null;
    }

    private function findCrossoverPosition(string $word): ?array
    {
        $wordLength = strlen($word);

        foreach ($this->reservedCoordinates as $coord) {
            $letter = $coord['letter'];
            $x = $coord['x'];
            $y = $coord['y'];

            for ($i = 0; $i < $wordLength; $i++) {
                if ($word[$i] === $letter) {
                    $hStartX = $x - $i;
                    $hStartY = $y;
                    if ($this->canPlaceHorizontally($hStartX, $hStartY, $word) && $this->isSingleLetterCross($hStartX, $hStartY, $word, 'horizontal')) {
                        return ['x' => $hStartX, 'y' => $hStartY, 'direction' => 'horizontal'];
                    }

                    $vStartX = $x;
                    $vStartY = $y - $i;
                    if ($this->canPlaceVertically($vStartX, $vStartY, $word) && $this->isSingleLetterCross($vStartX, $vStartY, $word, 'vertical')) {
                        return ['x' => $vStartX, 'y' => $vStartY, 'direction' => 'vertical'];
                    }
                }
            }
        }

        return null;
    }

    private function isSingleLetterCross(int $startX, int $startY, string $word, string $direction): bool
    {
        $wordLength = strlen($word);
        $crossCount = 0;

        for ($i = 0; $i < $wordLength; $i++) {
            $x = $direction === 'horizontal' ? $startX + $i : $startX;
            $y = $direction === 'horizontal' ? $startY : $startY + $i;

            if ($this->isReservedCoordinate($x, $y) && $this->isReservedCoordinate($x, $y) === $word[$i]) {
                $crossCount++;
            }

            if ($crossCount > 1) {
                return false;
            }
        }

        return $crossCount === 1;
    }

    private function solveWords(): void
    {
        $words = explode(' ', $this->text);

        foreach ($words as $index => $word) {
            $placed = false;

            if ($index == 0) {
                for ($attempt = 0; $attempt < 100; $attempt++) {
                    $positions = random_int(0, 1) === 1 ? $this->findHorizontalPosition($word) : $this->findVerticalPosition($word);
                    if ($positions) {
                        $placed = $positions['direction'] === 'horizontal'
                            ? $this->addHorizontalWord($positions['x'], $positions['y'], $word)
                            : $this->addVerticalWord($positions['x'], $positions['y'], $word);
                    }
                    if ($placed) {
                        break;
                    }
                }
            } else {
                $positions = $this->findCrossoverPosition($word);
                if ($positions) {
                    $placed = $positions['direction'] === 'horizontal'
                        ? $this->addHorizontalWord($positions['x'], $positions['y'], $word)
                        : $this->addVerticalWord($positions['x'], $positions['y'], $word);
                }

                if (!$placed) {
                    for ($attempt = 0; $attempt < 100; $attempt++) {
                        $positions = random_int(0, 1) === 1 ? $this->findHorizontalPosition($word) : $this->findVerticalPosition($word);
                        if ($positions) {
                            $placed = $positions['direction'] === 'horizontal'
                                ? $this->addHorizontalWord($positions['x'], $positions['y'], $word)
                                : $this->addVerticalWord($positions['x'], $positions['y'], $word);
                        }
                        if ($placed) {
                            break;
                        }
                    }
                }
            }

            if (!$placed) {
                throw new \Exception("Unable to place word: $word");
            }
        }
    }

    public function render(): array
    {
        $filename = uniqid() . '.png';
        $urlTMP = ROOT . '/tmp/' . $filename;

        $image = new \Imagick();
        $image->newImage(1100, 1100, $this->backgroundColor);
        $image->setImageFormat("png");

        if ($this->text) {
            $this->solveWords();
        }

        for ($ix = 1; $ix <= 25; $ix++) {
            for ($iy = 1; $iy <= 25; $iy++) {
                $reservedCoordinateLetter = $this->isReservedCoordinate($ix, $iy);
                if ($reservedCoordinateLetter) {
                    $image->drawImage($this->addLetter($reservedCoordinateLetter, $ix, $iy, $this->textColor));
                } else {
                    $letter = $this->getRandomLetter();
                    $image->drawImage($this->addLetter($letter, $ix, $iy, $this->textColorFaded));
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

    private function addLetter(string $letter, int $x, int $y, string $color): \ImagickDraw
    {
        $letter = mb_strtoupper($letter);

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
        return $letters[0];
    }

    private function addHorizontalWord(int $rowX, int $rowY, string $word): bool
    {
        $wordLength = strlen($word);
        if (!$this->canPlaceHorizontally($rowX, $rowY, $word)) {
            return false;
        }
        foreach (str_split($word) as $letter) {
            $this->reservedCoordinates[] = [
                'x' => $rowX,
                'y' => $rowY,
                'letter' => $letter,
            ];
            $rowX++;
        }
        return true;
    }

    private function addVerticalWord(int $rowX, int $rowY, string $word): bool
    {
        $wordLength = strlen($word);
        if (!$this->canPlaceVertically($rowX, $rowY, $word)) {
            return false;
        }
        foreach (str_split($word) as $letter) {
            $this->reservedCoordinates[] = [
                'x' => $rowX,
                'y' => $rowY,
                'letter' => $letter,
            ];
            $rowY++;
        }
        return true;
    }
}
