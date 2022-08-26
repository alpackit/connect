<div class="alpackit-container">
    <h2><?= __( 'Sync packits from staging', 'alpackit' );?></h2>
    <div id="packit-update-selection">
        <ul class="packit-list">
            <?php foreach( $unsynced as $packit ):?>
            <li class="packit-listing">
                    <label class="packit-header">
                        <input type="checkbox" value="<?= $packit['id'];?>" class="packit_id" checked />
                        <strong class="packit-title"><?= $packit['name'];?></strong>
                    </label>
                    <?php if( isset( $packit['local_version'] ) ):?>
                        <p>Local version: <span class="version"><?= $packit['local_version'];?></span></p>
                        <p>Staging version: <span class="version"><?= $packit['pivot']->version;?></span></p>
                    <?php else:?>
                        <span class="version"><?= esc_html__( 'Fresh_install', 'alpackit' );?></span>
                    <?php endif;?>
                </li>
            <?php endforeach;?>
        </ul>

        <div class="button-wrapper">
            <button class="button button-primary" id="sync-packits">Sync selected packits</button>
        </div>
    </div>
    <div id="packit-update-progress">
        <?php get_alpackit_template( 'components/progress-bar' );?>
        <?php get_alpackit_template( 'components/progress-list' );?>
    </div>

</div>