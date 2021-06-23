<?php

namespace tiFy\Partial\Partials\Breadcrumb;

class WpQueryPart
{
    /**
     * Liste des éléments à inclure dans le fil d'ariane
     * @var array
     */
    private $parts = [];

    /**
     * Récupération de la classe du contenu de l'élément
     *
     * @return string
     */
    public function getItemContentClass()
    {
        return 'tiFyPartial-breadcrumbItemContent';
    }

    /**
     * Récupération de la classe de l'encapsulation de l'élément
     *
     * @return string
     */
    public function getItemWrapperClass()
    {
        return 'tiFyPartial-breadcrumbItem';
    }

    /**
     * Récupération de la liste des éléments de contenu relatif à la requête globale de Wordpress
     *
     * @return array
     */
    public function getList()
    {
        // Page 404 - Contenu introuvable
        if (is_404()) :
            $this->parts[] = $this->linkRoot();
            $this->parts[] = $this->current404();

        // Page liste de résultats de recherche
        elseif (is_search()) :
            $this->parts[] = $this->linkRoot();
            $this->parts[] = $this->currentSearch();

        // Page de contenus associés à une taxonomie
        elseif (is_tax()) :
            $this->parts[] = $this->linkRoot();
            $this->parts[] = $this->currentTax();

        // Page d'accueil du site
        elseif (is_front_page()) :
            $this->parts[] = $this->currentPost();

        // Page liste des articles du blog
        elseif (is_home()) :
            if (get_option('page_for_posts')) :
                $this->parts[] = $this->linkRoot();
                $this->getAncestorsPartList();
                $this->parts[] = $this->currentHome();
            else :
                $this->parts[] = $this->linkRoot();
            endif;

        // Page de contenu de type fichier média
        elseif (is_attachment()) :
            $this->parts[] = $this->linkRoot();
            $this->parts[] = $this->currentPost();

        // Page de contenu de type post
        elseif (is_single()) :
            $this->parts[] = $this->linkRoot();
            $this->getAncestorsPartList();
            $this->parts[] = $this->currentPost();

        // Page de contenu de type page
        elseif (is_page()) :
            $this->parts[] = $this->linkRoot();
            $this->getAncestorsPartList();
            $this->parts[] = $this->currentPost();

        // Page liste de contenus associés à une catégorie
        elseif (is_category()) :
            $this->parts[] = $this->linkRoot();
            $this->parts[] = $this->currentCategory();

        // Page liste de contenus associés à un mot-clef
        elseif (is_tag()) :
            $this->parts[] = $this->linkRoot();
            $this->parts[] = $this->currentTag();

        // Page liste de contenus associés à un auteur
        elseif (is_author()) :
            $this->parts[] = $this->linkRoot();
            $this->parts[] = $this->currentAuthor();

        // Page liste de contenus relatifs à une date
        elseif (is_date()) :
            $this->parts[] = $this->linkRoot();
            $this->parts[] = $this->currentDate();

        // Pages liste de contenus
        elseif (is_archive()) :
            $this->parts[] = $this->linkRoot();
            $this->parts[] = $this->currentArchive();

        // Page liste de contenus paginé
        // @todo
        elseif (is_paged()) :

        endif;

        return $this->parts;
    }

    /**
     * Récupération du lien vers l'élèment racine.
     *
     * @param bool $current Indicateur d'élément courant.
     *
     * @return array
     */
    public function linkRoot()
    {
        if ($fp_post_id = get_option('page_on_front')) :
            $title = $this->getPostTitle($fp_post_id);

            $part = [
                'class'   => $this->getItemWrapperClass(),
                'content' => partial(
                    'tag',
                    [
                        'tag'     => 'a',
                        'attrs'   => [
                            'href'  => home_url('/'),
                            'title' => sprintf(__('Revenir à %s', 'tify'), $title),
                            'class' => $this->getItemContentClass()
                        ],
                        'content' => __('Accueil', 'tify')
                    ]
                )
            ];
        else :
            $part = [
                'class'   => $this->getItemWrapperClass(),
                'content' => partial(
                    'tag',
                    [
                        'tag'     => 'a',
                        'attrs'   => [
                            'href'  => home_url('/'),
                            'title' => sprintf(__('Revenir à l\'accueil du site %s', 'theme'), get_bloginfo('name')),
                            'class' => $this->getItemContentClass()
                        ],
                        'content' => __('Accueil', 'tify')
                    ]
                )
            ];
        endif;

        return $part;
    }

    /**
     * Récupération de l'élèment lors de l'affichage d'une page non trouvée 404
     *
     * @return array
     */
    public function current404()
    {
        $part = [
            'class'   => $this->getItemWrapperClass(),
            'content' => partial(
                'tag',
                [
                    'tag'     => 'span',
                    'attrs'   => [
                        'class' => $this->getItemContentClass()
                    ],
                    'content' => __('Erreur 404 - Page introuvable', 'tify')
                ]
            )
        ];

        return $part;
    }

    /**
     * Récupération de l'élèment lors de l'affichage d'une page liste de résultats de recherche
     *
     * @return array
     */
    public function currentSearch()
    {
        $part = [
            'class'   => $this->getItemWrapperClass(),
            'content' => partial(
                'tag',
                [
                    'tag'     => 'span',
                    'attrs'   => [
                        'class' => $this->getItemContentClass()
                    ],
                    'content' => sprintf(__('Résultats de recherche pour : "%s"', 'tify'), get_search_query()),
                ]
            )
        ];

        return $part;
    }

    /**
     * Récupération de l'élèment de page liste de contenus associés à une taxonomie
     *
     * @return array
     */
    public function currentTax()
    {
        /**
         * @var \WP_Term $term Terme de taxonomie courante
         */
        $term = get_queried_object();

        $part = [
            'class'   => $this->getItemWrapperClass(),
            'content' => partial(
                'tag',
                [
                    'tag'     => 'span',
                    'attrs'   => [
                        'class' => $this->getItemContentClass()
                    ],
                    'content' => sprintf('%s : %s', get_taxonomy($term->taxonomy)->label, $term->name),
                ]
            )
        ];

        return $part;
    }

    /**
     * Récupération de l'élèment lors de l'affichage d'une page liste des articles d'actualités (blog)
     *
     * @return array
     */
    public function currentHome()
    {
        global $wp_query;

        $part = [
            'class'   => $this->getItemWrapperClass(),
            'content' => partial(
                'tag',
                [
                    'tag'     => 'span',
                    'attrs'   => [
                        'class' => $this->getItemContentClass()
                    ],
                    'content' => is_paged()
                        ?  sprintf(
                                __('Page %d sur %d', 'tify'),
                                (($paged = get_query_var( 'paged' )? get_query_var('paged' ) : 1)),
                                $wp_query->max_num_pages
                            )
                        :  __('Actualités', 'tify')
                ]
            )
        ];

        return $part;
    }

    /**
     * Récupération de l'élèment lors de l'affichage d'une page de contenu seul (is_attachment|is_single|is_page)
     *
     * @return array
     */
    public function currentPost()
    {
        $part = [
            'class'   => $this->getItemWrapperClass(),
            'content' => partial(
                'tag',
                [
                    'tag'     => 'span',
                    'attrs'   => [
                        'class' => $this->getItemContentClass()
                    ],
                    'content' => $this->getPostTitle(get_the_ID())
                ]
            )
        ];

        return $part;
    }

    /**
     * Récupération de l'élèment lors de l'affichage d'une page liste de contenus relatifs à une catégorie
     *
     * @return array
     */
    public function currentCategory()
    {
        $category = get_category(get_query_var('cat'), false);

        $part = [
            'class'   => $this->getItemWrapperClass(),
            'content' => partial(
                'tag',
                [
                    'tag'     => 'span',
                    'attrs'   => [
                        'class' => $this->getItemContentClass()
                    ],
                    'content' => sprintf('Catégorie : %s', $category->name)
                ]
            )
        ];

        return $part;
    }

    /**
     * Récupération de l'élèment lors de l'affichage d'une page liste de contenus seul relatifs à un mot-clef
     *
     * @return array
     */
    public function currentTag()
    {
        $tag = get_tag( get_query_var( 'tag' ), false );

        $part = [
            'class'   => $this->getItemWrapperClass(),
            'content' => partial(
                'tag',
                [
                    'tag'     => 'span',
                    'attrs'   => [
                        'class' => $this->getItemContentClass()
                    ],
                    'content' => sprintf('Mot-Clef : %s', $tag->name)
                ]
            )
        ];

        return $part;
    }

    /**
     * Récupération de l'élèment lors de l'affichage d'une page liste de contenus relatifs à un auteur
     *
     * @return array
     */
    public function currentAuthor()
    {
        $name = get_the_author_meta('display_name', get_query_var('author'));

        $part = [
            'class'   => $this->getItemWrapperClass(),
            'content' => partial(
                'tag',
                [
                    'tag'     => 'span',
                    'attrs'   => [
                        'class' => $this->getItemContentClass()
                    ],
                    'content' => sprintf('Auteur : %s', $name)
                ]
            )
        ];

        return $part;
    }

    /**
     * Récupération de l'élèment lors de l'affichage d'une page liste de contenus relatifs à une date
     *
     * @return array
     */
    public function currentDate()
    {
        if (is_day()) :
            $content = sprintf(__('Archives du jour : %s', 'tify'), get_the_date());
        elseif (is_month()) :
            $content = sprintf(__('Archives du mois : %s', 'tify'), get_the_date('F Y'));
        elseif (is_year()) :
            $content = sprintf(__('Archives de l\'année : %s', 'tify'), get_the_date('Y'));;
        endif;

        $part = [
            'class'   => $this->getItemWrapperClass(),
            'content' => partial(
                'tag',
                [
                    'tag'     => 'span',
                    'attrs'   => [
                        'class' => $this->getItemContentClass()
                    ],
                    'content' => $content
                ]
            )
        ];

        return $part;
    }

    /**
     * Récupération de l'élèment lors de l'affichage d'une page liste de contenus
     *
     * @return array
     */
    public function currentArchive()
    {
        $content = (is_post_type_archive())
            ? post_type_archive_title('', false)
            : __('Actualités', 'tify');

        $part = [
            'class'   => $this->getItemWrapperClass(),
            'content' => partial(
                'tag',
                [
                    'tag'     => 'span',
                    'attrs'   => [
                        'class' => $this->getItemContentClass()
                    ],
                    'content' => $content
                ]
            )
        ];

        return $part;
    }

    /**
     * Intitulé de d'un élément relatif à un post
     *
     * @param int|\WP_Post $post
     *
     * @return string
     */
    protected function getPostTitle($post)
    {
        $post = \get_post($post);

        return esc_html(
            wp_strip_all_tags(
                get_the_title($post->ID)
            )
        );
    }

    /**
     * @todo Suppression des redondances current précédentes
     *
     * @return \tiFy\Partial\Partials\Tag\Tag
     */
    protected function partCurrent($attrs)
    {

    }

    /**
     * @todo Suppression des redondances link précédentes
     *
     * @return \tiFy\Partial\Partials\Tag\Tag
     */
    protected function partLink($attrs)
    {

    }

    /**
     * Récupération des ancêtres selon le contexte
     *
     * @return void
     */
    protected function getAncestorsPartList()
    {
        if (is_attachment()) :
            if ($parents = \get_ancestors(get_the_ID(), get_post_type())) :
                if (('post' === get_post_type(reset($parents))) && ($page_for_posts = get_option('page_for_posts'))) :
                    $title = $this->getPostTitle($page_for_posts);

                    $this->parts[] = [
                        'class'   => $this->getItemWrapperClass(),
                        'content' => partial(
                            'tag',
                            [
                                'tag'     => 'a',
                                'attrs'   => [
                                    'href'  => \get_permalink($page_for_posts),
                                    'title' => sprintf(__('Revenir à %s', 'tify'), $title),
                                    'class' => $this->getItemContentClass()
                                ],
                                'content' => $title
                            ]
                        )
                    ];
                endif;

                reset($parents);

                foreach (array_reverse($parents) as $parent) :
                    $title = $this->getPostTitle($parent);

                    $this->parts[] = [
                        'class'   => $this->getItemWrapperClass(),
                        'content' => partial(
                            'tag',
                            [
                                'tag'     => 'a',
                                'attrs'   => [
                                    'href'  => \get_permalink($parent),
                                    'title' => sprintf(__('Revenir à %s', 'tify'), $title),
                                    'class' => $this->getItemContentClass()
                                ],
                                'content' => $title
                            ]
                        )
                    ];
                endforeach;
            endif;
        elseif (is_home() && is_paged()) :
            if ($page_for_posts = get_option('page_for_posts')) :
                $title = $this->getPostTitle($page_for_posts);

                $this->parts[] = [
                    'class'   => $this->getItemWrapperClass(),
                    'content' => partial(
                        'tag',
                        [
                            'tag'     => 'a',
                            'attrs'   => [
                                'href'  => \get_permalink($page_for_posts),
                                'title' => sprintf(__('Revenir à %s', 'tify'), $title),
                                'class' => $this->getItemContentClass()
                            ],
                            'content' => $title
                        ]
                    )
                ];
            else :
                $this->parts[] = [
                    'class'   => $this->getItemWrapperClass(),
                    'content' => partial(
                        'tag',
                        [
                            'tag'     => 'a',
                            'attrs'   => [
                                'href'  => home_url('/'),
                                'title' => __('Revenir à la liste des actualités', 'tify'),
                                'class' => $this->getItemContentClass()
                            ],
                            'content' => __('Actualités', 'tify')
                        ]
                    )
                ];
            endif;
        elseif (is_single()) :
            // Le type du contenu est un article de blog
            if (is_singular('post')) :
                if ($page_for_posts = get_option('page_for_posts')) :
                    $title = $this->getPostTitle($page_for_posts);

                    $this->parts[] = [
                        'class'   => $this->getItemWrapperClass(),
                        'content' => partial(
                            'tag',
                            [
                                'tag'     => 'a',
                                'attrs'   => [
                                    'href'  => \get_permalink($page_for_posts),
                                    'title' => sprintf(__('Revenir à %s', 'tify'), $title),
                                    'class' => $this->getItemContentClass()
                                ],
                                'content' => $title
                            ]
                        )
                    ];
                else :
                    $this->parts[] = [
                        'class'   => $this->getItemWrapperClass(),
                        'content' => partial(
                            'tag',
                            [
                                'tag'     => 'a',
                                'attrs'   => [
                                    'href'  => home_url('/'),
                                    'title' => __('Revenir à la liste des actualités', 'tify'),
                                    'class' => $this->getItemContentClass()
                                ],
                                'content' => __('Actualités', 'tify')
                            ]
                        )
                    ];
                endif;

            // Le type de contenu autorise les pages d'archives
            elseif (($post_type_obj = get_post_type_object(get_post_type())) && $post_type_obj->has_archive) :
                $title = $post_type_obj->labels->name;

                $this->parts[] = [
                    'class'   => $this->getItemWrapperClass(),
                    'content' => partial(
                        'tag',
                        [
                            'tag'     => 'a',
                            'attrs'   => [
                                'href'  => \get_post_type_archive_link(\get_post_type()),
                                'title' => sprintf(__('Revenir à %s', 'tify'), $title),
                                'class' => $this->getItemContentClass()
                            ],
                            'content' => $title
                        ]
                    )
                ];
            endif;

            // Le contenu a des ancêtres
            if ($parents = get_ancestors(get_the_ID(), get_post_type())) :
                foreach (array_reverse($parents) as $parent) :
                    $title = $this->getPostTitle($parent);

                    $this->parts[] = [
                        'class'   => $this->getItemWrapperClass(),
                        'content' => partial(
                            'tag',
                            [
                                'tag'     => 'a',
                                'attrs'   => [
                                    'href'  => \get_permalink($parent),
                                    'title' => sprintf(__('Revenir à %s', 'tify'), $title),
                                    'class' => $this->getItemContentClass()
                                ],
                                'content' => $title
                            ]
                        )
                    ];
                endforeach;
            endif;

        elseif (is_page()) :
            if ($parents = get_ancestors(get_the_ID(), get_post_type())) :
                foreach (array_reverse($parents) as $parent) :
                    $title = $this->getPostTitle($parent);

                    $this->parts[] = [
                        'class'   => $this->getItemWrapperClass(),
                        'content' => partial(
                            'tag',
                            [
                                'tag'     => 'a',
                                'attrs'   => [
                                    'href'  => \get_permalink($parent),
                                    'title' => sprintf(__('Revenir à %s', 'tify'), $title),
                                    'class' => $this->getItemContentClass()
                                ],
                                'content' => $title
                            ]
                        )
                    ];
                endforeach;
            endif;
        endif;
    }
}