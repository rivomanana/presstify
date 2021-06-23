<?php

namespace tiFy\Metabox;

use tiFy\Contracts\Metabox\MetaboxWpPostController as MetaboxWpPostControllerContract;
use tiFy\Contracts\Metabox\MetaboxFactory;

abstract class MetaboxWpPostController extends MetaboxController implements MetaboxWpPostControllerContract
{
    /**
     * CONSTRUCTEUR.
     *
     * @param MetaboxFactory $item Instance de l'élément.
     * @param array $attrs Liste des variables passées en arguments.
     *
     * @return void
     */
    public function __construct(MetaboxFactory $item, $args = [])
    {
        parent::__construct($item, $args);

        foreach ($this->metadatas() as $meta => $single) :
            if (is_numeric($meta)) :
                $meta = (string) $single;
                $single = true;
            endif;

            post_type()->post_meta()->register($this->getPostType(), $meta, $single);
        endforeach;
    }

    /**
     * {@inheritdoc}
     */
    public function content($post = null, $args = null, $null = null)
    {
        return parent::content($post, $args, $null);
    }

    /**
     * {@inheritdoc}
     */
    public function getPostType()
    {
        return $this->getObjectName();
    }

    /**
     * {@inheritdoc}
     */
    public function header($post = null, $args = null, $null = null)
    {
        return parent::header($post, $args, $null);
    }

    /**
     * {@inheritdoc}
     */
    public function metadatas()
    {
        return [];
    }
}