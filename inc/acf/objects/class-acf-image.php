<?php
/**
 * Simple Image Object
 *
 * @package ChoctawNation
 * @since 0.2
 */

/** Generates a simple API for interacting with the ACF Image array and escapes its values */
class ACF_Image {
	/** The Image ID
	 *
	 * @var int $id
	 */
	public int $id;

	/** The img src url, escaped
	 *
	 * @var string $src
	 */
	public string $src;

	/** The srcset, escaped
	 *
	 * @var string $scrset;
	 */
	public string $srcset;

	/**
	 * The alt (user-input or title of image), escaped
	 *
	 * @var string $alt;
	 */
	public string $alt;

	/**
	 * Constructor
	 *
	 * @param array $image the acf image array
	 */
	public function __construct( array $image ) {
		$this->src    = esc_url( $image['url'] );
		$this->alt    = $this->generate_alt( $image );
		$this->srcset = wp_get_attachment_image_srcset( $image['ID'] );
		$this->id     = $image['ID'];
	}

	/** Sets the alt text to title if alt doesn't exist
	 *
	 * @param array $image the acf image array
	 */
	private function generate_alt( array $image ): string {
		if ( empty( $image['alt'] ) ) {
			return $image['title'];
		} else {
			return esc_attr( $image['alt'] );
		}
	}

	/**
	 * Generates the img element
	 *
	 * @param string $img_class the class to add
	 * @return string the HTML
	 */
	public function get_the_image( string $img_class = '' ): string {
		$markup = "<img class='{$img_class}' src='{$this->src}' srcset='{$this->srcset}' alt='{$this->alt}'/>";
		return $markup;
	}

	/**
	 * Echoes the Image element
	 *
	 * @param string $img_class the html class to give the image
	 */
	public function the_image( string $img_class = '' ) {
		echo $this->get_the_image( $img_class );
	}
}
