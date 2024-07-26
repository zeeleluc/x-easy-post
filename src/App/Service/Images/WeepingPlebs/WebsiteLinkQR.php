<?php
namespace App\Service\Images\WeepingPlebs;

use App\Models\DataSeeder;
use App\Service\Bucket;
use App\Service\Images\BaseTextImage;
use App\Service\Projects\Projects;
use App\Service\Traits\HasIdRange;
use App\Service\Traits\HasOptions;
use App\Service\Traits\HasOptionsPerId;
use App\Slack;
use chillerlan\QRCode\Common\GDLuminanceSource;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class WebsiteLinkQR extends BaseTextImage
{

    use HasIdRange;
    use HasOptions;
    use HasOptionsPerId;

    protected string $project = Projects::WEEPING_PLEBS;

    protected string $name = 'Regular NFT With QR Code
';

    private string $text = '';

    private string $type = '';

    private ?int $id = null;

    protected string $description = 'This image type gives a regular WeeipingPleb by ID (or random by property), including a QR code that leads to the WeepingPleb on the "metadata-website"';

    public function __construct(int $randomPunk = null)
    {
        $this->idRange = range(1, 8888);
        $this->optionsPerId = DataSeeder::get(DataSeeder::WEEPING_PLEBS_BY_ID);
        $this->options = array_keys($this->optionsPerId);
    }

    public static function make()
    {
        return new self();
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function render(): array
    {
        try {
            $filename = uniqid() . '.png';
            $urlTMP = ROOT . '/tmp/' . $filename;

            $image = new \Imagick();
            $image->newImage(800, 800, '#FFFFFF');
            $image->setImageFormat("png");

            # Image: "The Weeping Pleb"
            $this->pasteWeepingPleb($image, '#ffffff', 30, 200);

            # Text: "Weeping Pleb"
            $draw = new \ImagickDraw();
            $draw->setFont(ROOT . "/assets/fonts/plebs_-_official_text_font_1-webfont.ttf");
            $draw->setFontSize(80);
            $draw->setFillColor('#2E6EFD');
            $draw->annotation(40, 90, 'Weeping Pleb');
            $image->drawImage($draw);

            # Text: #123 (ID)
            $draw = new \ImagickDraw();
            $draw->setFont(ROOT . "/assets/fonts/Ubuntu-Light.ttf");
            $draw->setTextAlignment(\Imagick::ALIGN_RIGHT);
            $draw->setFontSize(25);
            $draw->setFillColor('#B30A21');
            $draw->annotation(485, 125, '#' . $this->id);
            $image->drawImage($draw);

//            # Create QR Code
//            $tempQR = ROOT . '/tmp/qr.png';
//            $data = 'https://weepingplebs.hasmints.com/weepingpleb/' . $this->id;
//            $options = new QROptions([
//                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
//                'eccLevel' => QRCode::ECC_L, // Error correction level
//                'scale' => 5, // Size of each QR code module
//            ]);
//            $qrcode = new QRCode($options);
//            $qrcode->render($data, $tempQR);
//
//            # Composite QR Code
//            $imagick = new \Imagick($tempQR);
//            $imagick->setImageBackgroundColor("gray");
//            $imagick->resizeimage(250, 250, \Imagick::FILTER_LANCZOS, 1.0, true);
//            $image->compositeImage($imagick, \Imagick::COMPOSITE_ATOP, 545, 100);

            # Arrow
            $arrow = new \Imagick(realpath(ROOT . '/assets/images/90-degrees-arrow.png'));
            $arrow->resizeimage(120, 120, \Imagick::FILTER_LANCZOS, 1.0, true);
            $arrow->rotateImage(new \ImagickPixel('rgba(0,0,0,0)'), -40);
            $image->compositeImage($arrow, \Imagick::COMPOSITE_ATOP, 570, 340);

            # Text: Check
            $draw = new \ImagickDraw();
            $draw->setFont(ROOT . "/assets/fonts/plebs_-_official_text_font_1-webfont.ttf");
            $draw->setFontSize(70);
            $draw->setFillColor('#2E6EFD');
            $draw->annotation(570, 580, 'Check');
            $image->drawImage($draw);

            # Text: Traits
            $draw = new \ImagickDraw();
            $draw->setFont(ROOT . "/assets/fonts/plebs_-_official_text_font_1-webfont.ttf");
            $draw->setFontSize(60);
            $draw->setFillColor('#2E6EFD');
            $draw->annotation(600, 645, 'Traits');
            $image->drawImage($draw);

            # Text: (footer) "www.weepingplebs.com"
            $draw = new \ImagickDraw();
            $draw->setTextAlignment(\Imagick::ALIGN_RIGHT);
            $draw->setFont(ROOT . "/assets/fonts/Ubuntu-Light.ttf");
            $draw->setFontSize(25);
            $draw->setFillColor('#000000');
            $draw->annotation(545, 755, 'www.weepingplebs.com');
            $image->drawImage($draw);

            $image->writeImage($urlTMP);

        } catch (\Exception $e) {
            (new Slack())->sendErrorMessage($e->getMessage());
        }

        return [
            'urlCDN' => $this->upload($urlTMP, $filename),
            'urlTMP' => $urlTMP,
        ];
    }

    private function pasteWeepingPleb(\Imagick &$image, string $color, int $x, int $y)
    {
        $resize = 48.6;

        if ($this->id) {
            $weepingPleb = $this->getWeepingPlebFixedSize($this->id, $resize);
        } elseif ($this->type) {
            $this->id = $this->getRandomIdForOption($this->type);
            $weepingPleb = $this->getWeepingPlebFixedSize($this->id, $resize);
        } else {
            $this->id = $this->getRandomId();
            $weepingPleb = $this->getWeepingPlebFixedSize($this->id, $resize);
        }

        // background square
        $draw = new \ImagickDraw();
        $color = new \ImagickPixel($color);
        $draw->setFillColor($color);
        $draw->rectangle($x, $y + 100, $x + 100, $y);
        $image->drawImage($draw);

        $image->compositeImage($weepingPleb, \Imagick::COMPOSITE_ATOP, $x, $y);
    }

    private function upload(string $urlTMP, string $filename): string
    {
        return (new Bucket())->uploadFile($urlTMP, $filename);
    }
}
