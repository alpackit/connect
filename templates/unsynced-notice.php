<div class="notice notice-warning is-dismissible packit-warning">
<h3>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v3.75m-9.303 3.376C1.83 19.126 2.914 21 4.645 21h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 4.88c-.866-1.501-3.032-1.501-3.898 0L2.697 17.626zM12 17.25h.007v.008H12v-.008z" />
    </svg>
    <?= esc_html__( 'Unsynced packits', 'alpackit' );?>
</h3>

<p>
    <?= esc_html__( 'You have plugins that are out of sync with their versions on ', 'alpackit'); ?>
    <a href="<?= esc_attr( env('ALPACKIT_STAGING_URL') );?>" target="_blank">Alpackit staging</a>
</p>
<div class="alpackit-link-wrapper">
    <a href="<?= admin_url('index.php?page=sync-packits')?>" class="button button-primary"><?= esc_html__( 'Automatically sync them', 'alpackit');?></a>
    <button class="link" id="reveal-unsynced-data"><?= esc_html__( 'More information', 'alpackit' );?></a>
</div>
<ul class="alpackit-update-overview">
<?php 
    foreach( $unsynced as $packit ){
        echo '<li>'.$packit['name'];
        echo ' - <b>staging version:</b> '.$packit['pivot']->version;
        echo ' - <b>local version:</b> '.$packit['local_version'];
        echo '</li>';
    }
?>
<ul>
</div>