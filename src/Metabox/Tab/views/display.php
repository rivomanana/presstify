<?php
?>
<div id="MetaboxTab-container--" class="MetaboxTab-container">
    <div class="hndle MetaboxTab-containerHeader">
        <h3 class="hndle">
            <span><?php echo $this->get('title'); ?></span>
        </h3>
    </div>

    <div id="MetaboxTab-wrapper--" class="MetaboxTab-wrapper">
        <div class="MetaboxTab-wrapperBack"></div>
        <div class="MetaboxTab-wrapperContent">
            <?php
            echo partial('tab', [
                'items' => $this->get('items', []),
                'rotation' => [
                    'left',
                    'top',
                    'default',
                    'pills'
                ]
            ]);
            ?>
        </div>
    </div>
</div>