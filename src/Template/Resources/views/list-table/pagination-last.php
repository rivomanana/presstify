<?php
/**
 * Pagination - Accès à la dernière page.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\Viewer $this
 * @var tiFy\Template\Templates\ListTable\Pagination $pagination
 * @var boolean $disabled
 * @var string $url
 */
if ($this->get('disabled')) :
    echo partial('tag', [
        'tag'     => 'span',
        'attrs'   => [
            'class'       => 'tablenav-pages-navspan',
            'aria-hidden' => 'true',
        ],
        'content' => '&raquo;',
    ]);
else :
    echo partial('tag', [
        'tag'     => 'a',
        'attrs'   => [
            'class' => 'last-page',
            'href'  => $this->get('url'),
        ],
        'content' => sprintf(
            "<span class=\"screen-reader-text\">%s</span><span aria-hidden=\"true\">%s</span>",
            __('Dernière page', 'tify'),
            '&raquo;'
        ),
    ]);
endif;