<?php namespace GameOfLife;


class Image {

	private $resource;

	/**
	 * Initiate a blank image
	 *
	 */
	 function __construct()
	{

		$this->create();
	}

	private function create() {

		// Create an image canvas
		$this->resource = imagecreatetruecolor(Config::getData('boardWidth'), Config::getData('boardHeight'));

		// Ensure the alpha channel is maintained
		imagesavealpha($this->resource, true);

		$this->setBackgroundTransparent();

	}

	public function renderCell($x, $y)
	{
		echo "Rendering $x $y
";
		imagefilledrectangle($this->resource, $x, $y, ($x+Config::getData('cellWidth')), ($y+Config::getData('cellHeight')), Config::getData('cellColour'));
	}

	/**
	 * Set the background of the image
	 *
	 * @param $red
	 * @param $green
	 * @param $blue
	 * @param $alpha
	 */
	private function setBackground($red,$green,$blue,$alpha) {

		// Set the background of the image
		$backgroundColor = imagecolorallocatealpha($this->resource, $red, $green, $blue, $alpha);
		imagefill($this->resource, 0, 0, $backgroundColor);
	}

	/**
	 * Set the background of the image with a hex code
	 *
	 * @param $hex
	 */
	public function setBackgroundHex($hex)
	{
		// Split the hex code into RGB
		list($red, $green, $blue) = sscanf($hex, "#%02x%02x%02x");

		// Pass the RGBA data to the background setter
		$this->setBackground($red, $green, $blue, 0);

	}

	/**
	 * Set the background of the images as transparent
	 */
	public function setBackgroundTransparent()
	{
		// Pass the RGBA data to the background setter
		$this->setBackground(0, 0, 0, 127);
	}

	/**
	 * Set the colour of the cells
	 *
	 * @param $red
	 * @param $green
	 * @param $blue
	 * @param $alpha
	 */
	private function setCellColour($red,$green,$blue,$alpha) {

		// Set the cell colour
		Config::setData('cellColour', imagecolorallocatealpha($this->resource, $red, $green, $blue, $alpha));
	}

	/**
	 * Set the colour of the cells with a hex code
	 *
	 * @param $hex
	 */
	public function setCellColourHex($hex)
	{
		// Split the hex code into RGB
		list($red, $green, $blue) = sscanf($hex, "#%02x%02x%02x");

		// Pass the RGBA data to the cell colour setter
		$this->setCellColour($red, $green, $blue, 0);

	}

	/**
	 * Write the image data to a file
	 *
	 * @param $path
	 */
	public function write($path)
	{
		// Write the image data to a file
		imagepng($this->resource,$path.".png");
	}

	function __destruct() {
		$this->destroy();
	}

	/**
	 * Cleanup an image resource to free up memory
	 */
	public function destroy()
	{
		imagedestroy($this->resource);
		unset($this->resource);
	}

}