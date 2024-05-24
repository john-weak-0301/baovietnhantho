<?php

namespace App\Model;

use Core\Database\Helpers\Sluggable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Block
 *
 * @mixin \Eloquent
 */
class Block extends Model
{
    use Sluggable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gutenberg_blocks';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['content', 'title', 'type'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'raw_content', 'raw_title', 'rendered_content',
    ];

    /**
     * Sluggable configuration.
     *
     * @var array
     */
    protected $sluggable = [
        'slug' => [
            'source'    => 'raw_title',
            'maxLength' => 100,
            'separator' => '-',
            'onUpdate'  => true,
        ],
    ];

    /**
     * Returns the rendered content of the block
     *
     * @return String - The completely rendered content
     */
    public function render()
    {
        return the_content($this->rendered_content);
    }

    /**
     * Renders the content of the Block object
     *
     * @return String
     */
    public function renderRaw()
    {
        $this->rendered_content = $this->raw_content;

        return $this->rendered_content;
    }

    /**
     * Sets the raw content and performs some initial rendering
     *
     * @param $content
     */
    public function setContent($content)
    {
        $this->raw_content = $content;

        $this->renderRaw();
    }

    /**
     * Returns a content object similar to WordPress
     *
     * @return array
     */
    public function getContentAttribute()
    {
        return [
            'raw'       => $this->raw_content,
            'rendered'  => $this->rendered_content,
            'protected' => false,
        ];
    }

    /**
     * Returns a content object similar to WordPress
     *
     * @return array
     */
    public function getTitleAttribute()
    {
        return [
            'raw' => $this->raw_title,
        ];
    }

    public function getStatusAttribute()
    {
        return 'publish';
    }

    public function getTypeAttribute()
    {
        return 'wp_block';
    }
}
