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
	/**
	 * The post id
	 *
	 * @var int $post_id;
	 */
	private int $post_id;

	/**
	 * Whether post is featured
	 *
	 * @var bool $is_featured
	 */
	public bool $is_featured;

	/**
	 * Appears below the post title.
	 *
	 * @var ?string $subheadline;
	 */
	private ?string $subheadline;

	/**
	 * Whether post has photo
	 *
	 * @var bool $has_photo
	 */
	public bool $has_photo;

	/**
	 * The photo credit
	 *
	 * @var ?string $photo_credit
	 */
	private ?string $photo_credit;

	/**
	 * The photo caption
	 *
	 * @var ?string $photo_caption
	 */
	private ?string $photo_caption;

	/** The actual article text.
	 *
	 * @var string $article
	 */
	private string $article;

	/**
	 * An array of WP_Post objects representing the boilerplates to add to the news post.
	 *
	 * @var ?\WP_Post[] $boilerplates
	 */
	private ?array $boilerplates;

	/** Whether the post has associated boilerplates
	 *
	 * @var bool $has_boilerplates
	 */
	public bool $has_boilerplates;

	/**
	 * If this is a copy of an external article, this will be `true`
	 *
	 * @var bool $has_external_link
	 */
	public bool $has_external_link;

	/**
	 * The external article's title
	 *
	 * @var string $external_article_title
	 */
	private string $external_article_title;

	/**
	 * The link to the original article
	 *
	 * @var string $external_article_link
	 */
	private string $external_article_link;

	/**
	 * The author of the original article
	 *
	 * @var string $external_article_author
	 */
	private string $external_article_author;

	/**
	 * The published date of the original author as a DateTime object
	 *
	 * @var ?\DateTime $external_article_published_date
	 */
	private ?\DateTime $external_article_published_date;

	/**
	 * Whether or not this post has a video
	 *
	 * @var bool $has_video
	 */
	public bool $has_video;

	/**
	 * The Vimeo id of the video
	 *
	 * @var ?int $video_id
	 */
	private ?int $video_id;

	/**
	 * The article excerpt (also the Yoast "Brief Description" or "Archive Content")
	 *
	 * @var string $excerpt
	 */
	private string $excerpt;

	/**
	 * Inits the class properties with the passed $id param (e.g. `get_field( 'field_name', $id )`)
	 *
	 * @param int $id the Post ID
	 */
	public function __construct( int $id ) {
		$this->post_id     = $id;
		$this->is_featured = get_field( 'featured_post', $id );
		$this->subheadline = ! empty( get_field( 'subheading', $id ) ) ? esc_textarea( get_field( 'subheading', $id ) ) : null;
		$this->article     = acf_esc_html( get_field( 'article', $id ) );
		$this->excerpt     = esc_textarea( get_field( 'archive_content', $id ) );

		$boilerplates           = get_field( 'additional_boilerplates', $id );
		$this->has_boilerplates = is_array( $boilerplates ) && count( $boilerplates ) > 0;
		$this->boilerplates     = $this->has_boilerplates ? $boilerplates : null;

		$this->has_video = ! empty( get_field( 'video', $id ) );
		$this->video_id  = $this->has_video ? get_field( 'video', $id ) : null;

		$this->set_photo_props( get_field( 'photo_meta', $id ) );
		$this->set_full_article_props( get_field( 'full_article', $id ) );
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
		$this->external_article_author = esc_textarea( $acf['author'] );
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
	 * @param string       $size [Optional] The thumbnail size
	 * @param array|string $attr [Optional] Query string or array of attrbitues. Default empty.
	 */
	public function get_the_photo( string $size = 'full', array|string $attr = '' ): string {
		$image = get_the_post_thumbnail( $this->post_id, $size );
		if ( ! empty( $attr ) ) {
			$image = get_the_post_thumbnail( $this->post_id, $size, $attr );
		}
		return $image;
	}

	/**
	 * Echoes `get_the_photo($size)`, a wrapper for `get_the_post_thumbnail`
	 *
	 * @param string       $size [Optional] The thumbnail size
	 * @param array|string $attr [Optional] Query string or array of attrbitues. Default empty.
	 */
	public function the_photo( string $size = 'full', array|string $attr = '' ) {
		echo $this->get_the_photo( $size, $attr );
	}

	/**
	 * Gets the photo credit inside a `span.photo-meta__credit` or returns an empty string.
	 *
	 * @return string the markup
	 */
	public function get_the_photo_credit(): string {
		$markup = '';
		if ( null !== $this->photo_credit ) {
			$markup = "<p class='photo-meta__credit'>{$this->photo_credit}</p>";
		}
		return $markup;
	}

	/**
	 * Gets the photo caption inside a `span.photo-meta__caption` or returns an empty string.
	 *
	 * @return string the markup
	 */
	public function get_the_photo_caption(): ?string {
		$markup = '';
		if ( null !== $this->photo_caption ) {
			$markup = "<p class='photo-meta__caption'>{$this->photo_caption}</p>";
		}
		return $markup;
	}

	/**
	 * Echoes the photo credit inside a `span.photo-meta__credit` or returns an empty string.
	 */
	public function the_photo_credit() {
		echo $this->get_the_photo_credit();
	}

	/**
	 * Echoes the photo caption inside a `span.photo-meta__caption` or returns an empty string.
	 */
	public function the_photo_caption() {
		echo $this->get_the_photo_caption();
	}

	//phpcs:ignore
	public function get_the_article(): string {
		return $this->article;
	}

	//phpcs:ignore
	public function the_article() {
		echo $this->get_the_article();
	}

	/**
	 * Loops through each attached boilerplate (a WP_Post) and returns the markup.
	 *
	 * @see \ChoctawNation\News\Boilerplate::get_the_boilerplate() `get_the_boilerplate()`
	 * @return string the markup
	 */
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

	/**
	 * Loops through each attached boilerplate (a WP_Post) and echoes the markup.
	 *
	 * @see \ChoctawNation\News\Boilerplate::get_the_boilerplate() `get_the_boilerplate()`
	 */
	public function the_boilerplates() {
		echo $this->get_the_boilerplates();
	}

	/**
	 * Gets the Published Date in a specified format (default 'F j, Y')
	 *
	 * @param string $format the date format
	 */
	public function get_the_published_date( string $format = 'F j, Y' ): string {
		$markup = 'Published ' . get_the_date( $format, $this->post_id );
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
	 * Gets external article's title
	 *
	 * @return string the title
	 */
	public function get_the_external_article_title(): string {
		return $this->external_article_title;
	}

	/**
	 * Echoes external article's title
	 */
	public function the_external_article_title() {
		echo $this->get_the_external_article_title();
	}

	/**
	 * Returns the link to the original article
	 *
	 * @return string the external article link
	 */
	public function get_the_external_article_link(): string {
		return $this->external_article_link;
	}

	/**
	 * Echoes the link to the original article
	 */
	public function the_external_article_link() {
		echo $this->get_the_external_article_link();
	}

	/**
	 * Returns the author of the original article
	 *
	 * @return string the external article author
	 */
	public function get_the_external_article_author(): string {
		return $this->external_article_author;
	}

	/**
	 * Echoes the author of the original article
	 */
	public function the_external_article_author() {
		echo $this->get_the_external_article_author();
	}

	/**
	 * Gets the original article's published date in a specified format (default 'F j, Y')
	 *
	 * @param string $format the date format
	 */
	public function get_the_external_article_publish_date( string $format = 'F j, Y' ): string {
		return $this->external_article_published_date->format( $format );
	}

	/**
	 * Echoes the original article's published date in a specified format (default 'F j, Y')
	 *
	 * @param string $format the date format
	 */
	public function the_external_article_publish_date( string $format = 'F j, Y' ) {
		echo $this->get_the_external_article_publish_date( $format );
	}

	/**
	 * Returns the video inside a `.article__video.embed-container` and `lite-vimeo` player
	 */
	public function get_the_video(): string {
		$markup = "<div class='article__video embed-container p-0'><lite-vimeo videoid='{$this->video_id}'></lite-vimeo></div>";
		return $markup;
	}

	/**
	 * Echoes the video inside a `.article__video.embed-container` and `lite-vimeo` player
	 */
	public function the_video() {
		echo $this->get_the_video();
	}

	/**
	 * Returns the excerpt (if set) or the first 155 characters of the article with a trailing ellipses
	 *
	 * @return string the excerpt
	 */
	public function get_the_excerpt(): string {
		$markup = '';
		if ( empty( $this->excerpt ) ) {
			$markup = substr( $this->article, 0, 155 ) . '...';
		} else {
			$markup = $this->excerpt;
		}
		return $markup;
	}

	/**
	 * Echoes the excerpt (if set) or the first 155 characters of the article with a trailing ellipses
	 */
	public function the_excerpt() {
		echo $this->get_the_excerpt();
	}
}
