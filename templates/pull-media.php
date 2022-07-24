<div class="alpackit-container">
    <h2><?= __( 'Pull media from staging', 'alpackit' );?></h2>
    <?php get_alpackit_template( 'components/progress-bar' );?>
    
    <div class="button-wrapper">
        <button class="button" id="sync-media">Sync media</button>
    </div>
    <?php get_alpackit_template( 'components/progress-list' );?>
</div>