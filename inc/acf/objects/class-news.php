<?php
/**
 * The News Object
 * Used to create a consistent API with the ACF Fields
 *
 * @since 1.0
 * @package ChoctawNation
 * @subpackage News
 */

namespace ChoctawNation\News;

/**
 * A simple API for interacting with the ACF Fields
 */
class News {
	private int $post_id;
	public bool $is_featured;
	private ?string $subheadline;
	public bool $has_photo;
	private ?string $photo_credit;
	private ?string $photo_caption;
	private string $article;
	/**
	 * An array of WP_Post objects representing the boilerplates to add to the news post.
	 *
	 * @var ?\WP_Post[] $boilerplates
	 */
	private ?array $boilerplates;
	public bool $has_external_link;
	private string $external_article_title;
	private string $external_article_link;
	private string $external_article_author;
	private ?\DateTime $external_article_published_date;
	public bool $has_video;
	private int $video_id;
	private string $excerpt;


	public function __construct( int $id ) {
		$this->post_id     = $id;
		$this->is_featured = get_field( 'featured_post', $id );
		$this->subheadline = ! empty( get_field( 'subheading', $id ) ) ? esc_textarea( get_field( 'subheading', $id ) ) : null;
		$this->set_photo_props( get_field( 'photo_meta', $id ) );
		$this->article      = acf_esc_html( get_field( 'article', $id ) );
		$boilerplates       = get_field( 'additional_boilerplates', $id );
		$this->boilerplates = is_array( $boilerplates ) && ( count( $boilerplates ) > 0 ) ? $boilerplates : null;
		$this->set_full_article_props( get_field( 'full_article', $id ) );
		$this->has_video = ! empty( get_field( 'video', $id ) );
		$this->video_id  = get_field( 'video', $id );
		$this->excerpt   = esc_textarea( get_field( 'archive_content', $id ) );
	}

	/** Sets photo props
	 *
	 * @param array $acf the Photo Meta subgroup
	 */
	private function set_photo_props( array $acf ) {
		$this->has_photo    = has_post_thumbnail( $this->post_id );
		$this->photo_credit = ! empty( $acf['photo_credit'] ) ? esc_textarea( $acf['photo_credit'] ) : null;
		if ( ! empty( $acf['photo_caption'] ) ) {
			$this->photo_caption = esc_textarea( $acf['photo_caption'] );
		} elseif ( $this->has_photo ) {
			$this->photo_caption = get_the_post_thumbnail_caption( $this->post_id );
		} else {
			$this->photo_caption = null;
		}
	}

	/** Sets Full (external) Article props
	 *
	 * @param array $acf the Full Article subgroup
	 */
	private function set_full_article_props( array $acf ) {
		$this->has_external_link       = $acf['has_link_to_full_article'];
		$this->external_article_title  = esc_textarea( $acf['title'] );
		$this->external_article_link   = esc_url( $acf['link'] );
		$this->external_article_author = esc_url( $acf['author'] );
		if ( ! empty( $acf['published_date'] ) ) {
			$datetime                              = \DateTime::createFromFormat( 'M j, Y', $acf['published_date'], new \DateTimeZone( 'America/Chicago' ) );
			$this->external_article_published_date = $datetime;
		} else {
			$this->external_article_published_date = null;
		}
	}

	//phpcs:ignore
	public function get_the_subheadline(): ?string {
		return $this->subheadline;
	}

	//phpcs:ignore
	public function the_subheadline() {
		echo $this->get_the_subheadline();
	}

	/**
	 * Wrapper for `get_the_post_thumbnail`
	 *
	 * @param string $size [Optional] The thumbnail size
	 */
	public function get_the_photo( string $size = 'full' ): string {
		return get_the_post_thumbnail( $this->post_id, $size );
	}

	/**
	 * Echoes `get_the_photo($size)`, a wrapper for `get_the_post_thumbnail`
	 *
	 * @param string $size [Optional] The thumbnail size
	 */
	public function the_photo( string $size = 'full' ) {
		echo $this->get_the_photo( $size );
	}

	public function get_the_photo_credit(): ?string {
		$markup = "<span class='photo-meta__credit'>" . $this->photo_credit . '</span>';
		return $markup;
	}
	public function get_the_photo_caption(): ?string {
		$markup = "<p class='photo-meta__caption'>" . $this->photo_caption . '</p>';
		return $markup;
	}

	public function the_photo_credit() {
		echo $this->get_the_photo_credit();
	}

	public function the_photo_caption() {
		echo $this->get_the_photo_caption();
	}

	//phpcs:ignore
	public function get_the_article():string {
		return $this->article;
	}

	//phpcs:ignore
	public function the_article() {
		echo $this->get_the_article();
	}

	public function get_the_boilerplates(): string {
		$markup = '';
		if ( $this->boilerplates ) {
			foreach ( $this->boilerplates as $boilerplate ) {
				$plate   = new Boilerplate( $boilerplate );
				$markup .= $plate->get_the_boilerplate();
			}
		}
		return $markup;
	}

	public function the_boilerplates() {
		echo $this->get_the_boilerplates();
	}

	/**
	 * Gets the Published Date in a specified format (default 'F j, Y')
	 *
	 * @param string $format the date format
	 */
	public function get_the_published_date( string $format = 'F j, Y' ): string {
		$markup  = 'Published ';
		$markup .= $this->external_article_published_date->format( $format ) ?? get_the_date( $format );
		return $markup;
	}

	/**
	 * Echoes the Published Date in a specified format (default 'F j, Y')
	 *
	 * @param string $format the date format
	 */
	public function the_published_date( string $format = 'F j, Y' ) {
		echo $this->get_the_published_date( $format );
	}

	/**
	 * Returns the video inside a `.article__video.embed-container` and `lite-vimeo` player
	 */
	public function get_the_video(): string {
		$markup = "<div class='article__video embed-container'><lite-vimeo videoid='{$this->video_id}'></lite-vimeo></div>";
		return $markup;
	}

	/**
	 * Echoes the video inside a `.article__video.embed-container` and `lite-vimeo` player
	 */
	public function the_video() {
		echo $this->get_the_video();
	}

	public function get_the_excerpt(): string {
		$markup = '';
		if ( empty( $this->excerpt ) ) {
			$markup = substr( $this->article, 0, 155 ) . '...';
		} else {
			$markup = $this->excerpt;
		}
		return $markup;
	}

	public function the_excerpt() {
		echo $this->get_the_excerpt();
	}
}
