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
	 * @var ?string $excerpt
	 */
	private ?string $excerpt;

	/**
	 * Inits the class properties with the passed $id param (e.g. `get_field( 'field_name', $id )`)
	 *
	 * @param int       $news_post_id the Post ID
	 * @param int|int[] $boilerplate_ids [Optional] default Boilerplate IDs to attach to every news post
	 */
	public function __construct( int $news_post_id, $boilerplate_ids = null ) {
		$this->post_id     = $news_post_id;
		$this->is_featured = get_field( 'featured_post', $news_post_id );
		$this->subheadline = ! empty( get_field( 'subheading', $news_post_id ) ) ? esc_textarea( get_field( 'subheading', $news_post_id ) ) : null;
		$this->article     = acf_esc_html( get_field( 'article', $news_post_id ) );
		$this->excerpt     = get_field( 'archive_content', $news_post_id ) ? esc_textarea( get_field( 'archive_content', $news_post_id ) ) : null;
		$this->set_the_boilerplate_props( $news_post_id, $boilerplate_ids );

		$this->has_video = ! empty( get_field( 'video', $news_post_id ) );
		$this->video_id  = $this->has_video ? get_field( 'video', $news_post_id ) : null;

		$this->set_photo_props( get_field( 'photo_meta', $news_post_id ) );
		$this->set_full_article_props( get_field( 'full_article', $news_post_id ) );
	}

	/**
	 * Inits the boilerplates and sets the `has_boilerplates` property
	 *
	 * @param int            $news_post_id the Post ID
	 * @param int|int[]|null $default_boilerplate_ids [Optional] default Boilerplate IDs to attach to every news post
	 * @return void
	 */
	private function set_the_boilerplate_props( int $news_post_id, $default_boilerplate_ids ) {
		$selected_boilerplates = (array) get_field( 'additional_boilerplates', $news_post_id );

		if ( empty( $selected_boilerplates ) && empty( $default_boilerplate_ids ) ) {
			$this->boilerplates     = null;
			$this->has_boilerplates = false;
		} else {
			$this->has_boilerplates = true;
			$default_boilerplates   = array();
			foreach ( (array) $default_boilerplate_ids as $boilerplate_id ) {
				$default_boilerplates[] = get_post( $boilerplate_id );
			}
			$boilerplates       = array_unique( array_merge( $selected_boilerplates, $default_boilerplates ), SORT_REGULAR );
			$this->boilerplates = $boilerplates;
		}
	}

	/**
	 * Sets photo props
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

	/**
	 * Gets the subheadline
	 *
	 * @return ?string the subheadline or null
	 */
	public function get_the_subheadline(): ?string {
		return $this->subheadline;
	}

	/**
	 * Echoes the subheadline
	 *
	 * @return void
	 */
	public function the_subheadline(): void {
		echo $this->get_the_subheadline();
	}

	/**
	 * Wrapper for `get_the_post_thumbnail`
	 *
	 * @param string       $size [Optional] The thumbnail size
	 * @param array|string $attr [Optional] Query string or array of attrbitues. Default empty.
	 * @return string the image
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
	 * @return void
	 */
	public function the_photo( string $size = 'full', array|string $attr = '' ): void {
		echo $this->get_the_photo( $size, $attr );
	}

	/**
	 * Gets the photo credit inside a `span.photo-meta__credit` or returns an empty string.
	 *
	 * @param string|string[] $classes [Optional] Additional classes to add to the span
	 * @return string the markup
	 */
	public function get_the_photo_credit( $classes = '' ): string {
		$classes = $this->get_the_classes( 'photo-meta__credit', $classes );
		$markup  = '';
		if ( null !== $this->photo_credit ) {
			$markup = '<span class="' . implode( ' ', $classes ) . '">' . $this->photo_credit . '</span>';
		}
		return $markup;
	}

	/**
	 * Gets the photo caption inside a `span.photo-meta__caption` or returns an empty string.
	 *
	 * @param string|string[] $classes [Optional] Additional classes to add to the span
	 * @return string the markup
	 */
	public function get_the_photo_caption( $classes = '' ): string {
		$classes = $this->get_the_classes( 'photo-meta__caption', $classes );
		$markup  = '';
		if ( null !== $this->photo_caption ) {
			$markup = '<span class="' . implode( ' ', $classes ) . '">' . $this->photo_caption . '</span>';
		}
		return $markup;
	}

	/**
	 * Merges the default class with the passed classes
	 *
	 * @param string|string[] $default_class the default class(es)
	 * @param string|string[] $classes [Optional] Additional classes to add
	 * @return string[] the classes
	 */
	private function get_the_classes( $default_class, $classes ): array {
		if ( empty( $classes ) ) {
			return array( $default_class );
		} else {
			return array_merge( (array) $default_class, (array) $classes );
		}
	}

	/**
	 * Echoes the photo credit inside a `span.photo-meta__credit` or returns an empty string.
	 *
	 * @param string|string[] $classes [Optional] Additional classes to add to the span
	 * @return void
	 */
	public function the_photo_credit( $classes = '' ): void {
		echo $this->get_the_photo_credit( $classes );
	}

	/**
	 * Echoes the photo caption inside a `span.photo-meta__caption` or returns an empty string.
	 *
	 * @param string|string[] $classes [Optional] Additional classes to add to the span
	 * @return void
	 */
	public function the_photo_caption( string $classes = '' ): void {
		echo $this->get_the_photo_caption( $classes );
	}

	/**
	 * Gets the article text
	 *
	 * @return string the article
	 */
	public function get_the_article(): string {
		return $this->article;
	}

	/**
	 * Echoes the article text
	 *
	 * @return void
	 */
	public function the_article(): void {
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
	 * @param string $format [Optional] the date format
	 * @return string the date
	 */
	public function get_the_published_date( string $format = 'F j, Y' ): string {
		$markup = 'Published ' . get_the_date( $format, $this->post_id );
		return $markup;
	}

	/**
	 * Echoes the Published Date in a specified format (default 'F j, Y')
	 *
	 * @param string $format [Optional] the date format
	 * @return void
	 */
	public function the_published_date( string $format = 'F j, Y' ): void {
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
	 *
	 * @return void
	 */
	public function the_external_article_title(): void {
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
	 *
	 * @return void
	 */
	public function the_external_article_link(): void {
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
	 *
	 * @return void
	 */
	public function the_external_article_author(): void {
		echo $this->get_the_external_article_author();
	}

	/**
	 * Gets the original article's published date in a specified format (default 'F j, Y')
	 *
	 * @param string $format [Optional] the date format
	 * @return string the date
	 */
	public function get_the_external_article_publish_date( string $format = 'F j, Y' ): string {
		return $this->external_article_published_date->format( $format );
	}

	/**
	 * Echoes the original article's published date in a specified format (default 'F j, Y')
	 *
	 * @param string $format [Optional] the date format
	 * @return void
	 */
	public function the_external_article_publish_date( string $format = 'F j, Y' ): void {
		echo $this->get_the_external_article_publish_date( $format );
	}

	/**
	 * Returns the video inside a `.article__video.ratio.ratio-16x9` and `lite-vimeo` player
	 *
	 * @param string|string[] $classes [Optional] Additional classes to add to the div
	 * @return string the markup
	 */
	public function get_the_video( $classes = '' ): string {
		$classes = array_merge( array( 'article__video', 'ratio', 'ratio-16x9' ), (array) $classes );
		$markup  = '<div class="' . implode( ' ', $classes ) . '"><lite-vimeo videoid="' . $this->video_id . '"></lite-vimeo></div>';
		return $markup;
	}

	/**
	 * Echoes the video inside a `.article__video.ratio.ratio-16x9` and `lite-vimeo` player
	 *
	 * @param string|string[] $classes [Optional] Additional classes to add to the div
	 * @return void
	 */
	public function the_video( $classes = '' ): void {
		echo $this->get_the_video( $classes );
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
