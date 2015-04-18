<?php namespace GameOfLife;


class Image {

	private $resource;

	/**
	 * Initiate a blank image
	 *
	 */
	 function __construct()
	{
		// Create an image canvas
		$image = imagecreatetruecolor($this->board['width'], $this->board['height']);

		// Ensure the alpha channel is maintained
		imagesavealpha($image, true);

		// Save this image
		$this->resource = $image;
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
	 * Write the image data to a file
	 *
	 * @param $path
	 */
	public function write($path)
	{
		// Write the image data to a file
		imagepng($this->resource,$path.".png");
	}

}