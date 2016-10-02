<?php namespace GameOfLife;

class Cell
{
    const WIDTH = 13;
    const HEIGHT = 13;
    const SPACING = 3;

    protected $alive = false;

    public $colour = [
        'R' => 85,
        'G' => 152,
        'B' => 215,
        'A' => 100,
    ];

    protected $positionX;
    protected $positionY;

    /**
     * Cell constructor.
     * @param int $positionX
     * @param int $positionY
     */
    public function __construct($positionX, $positionY)
    {
        $this->positionX = $positionX;
        $this->positionY = $positionY;
    }

    /**
     * Set the colour of the cells
     *
     * @param $red
     * @param $green
     * @param $blue
     * @param $alpha
     */
    public function setColour($red, $green, $blue, $alpha)
    {
        $this->colour = [
            'R' => $red,
            'G' => $green,
            'B' => $blue,
            'A' => $alpha
        ];
    }

    /**
     * Set the colour of the cells with a hex code
     *
     * @param $hex
     * @param int $alpha
     */
    public function setColourByHex($hex, $alpha = 0)
    {
        // Split the hex code into RGB
        list($red, $green, $blue) = sscanf($hex, "#%0x%02x%02x");

        // Pass the RGBA data to the cell colour setter
        $this->setColour($red, $green, $blue, $alpha);
    }

    public function birth()
    {
        $this->alive = true;
    }

    public function kill()
    {
        $this->alive = false;
    }

    public function isAlive()
    {
        return $this->alive;
    }

    public function getPositionX()
    {
        return $this->positionX;
    }

    public function getPositionY()
    {
        return $this->positionY;
    }

}