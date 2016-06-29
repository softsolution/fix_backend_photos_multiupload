<?php
if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.6                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2015                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

    $cfg = $inCore->loadComponentConfig('photos');

	cmsCore::loadClass('photo');
    cmsCore::loadModel('photos');
    $model = new cms_model_photos();

    $opt = cmsCore::request('opt', 'str', 'list_albums');

//=================================================================================================//

	if($opt=='saveconfig'){

		if(!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

		$cfg = array();
		$cfg['link']                = cmsCore::request('show_link', 'int', 0);
        $cfg['saveorig']            = cmsCore::request('saveorig', 'int', 0);
        $cfg['maxcols']             = cmsCore::request('maxcols', 'int', 0);
        $cfg['orderby']             = cmsCore::request('orderby', 'str', '');
        $cfg['orderto']             = cmsCore::request('orderto', 'str', '');
        $cfg['showlat']             = cmsCore::request('showlat', 'int', 0);
        $cfg['watermark']           = cmsCore::request('watermark', 'int', 0);
        $cfg['meta_keys']           = cmsCore::request('meta_keys', 'str', '');
        $cfg['meta_desc']           = cmsCore::request('meta_desc', 'str', '');
        $cfg['seo_user_access']     = cmsCore::request('seo_user_access', 'int', 0);
        $cfg['best_latest_perpage'] = cmsCore::request('best_latest_perpage', 'int', 0);
        $cfg['best_latest_maxcols'] = cmsCore::request('best_latest_maxcols', 'int', 0);

        $inCore->saveComponentConfig('photos', $cfg);

		cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');

		cmsCore::redirectBack();

	}

//=================================================================================================//
//=================================================================================================//

	if ($opt=='list_albums'|| $opt=='list_photos'){

        $toolmenu[] = array('icon'=>'newfolder.gif', 'title'=>$_LANG['AD_ALBUM_ADD'], 'link'=>'?view=components&do=config&id='.$id.'&opt=add_album');
        
        $toolmenu[] = array('icon'=>'newphoto.gif', 'title' => 'Новая фотография', 'link' => '?view=components&do=config&id='.$id.'&opt=add_photo');

	$toolmenu[] = array('icon'=>'newphotomulti.gif', 'title' => 'Массовая загрузка фото', 'link' => '?view=components&do=config&id='.$id.'&opt=add_photo_multi');
        $toolmenu[] = array('icon'=>'folders.gif', 'title'=>$_LANG['AD_ALBUMS'], 'link'=>'?view=components&do=config&id='.$id.'&opt=list_albums');
        $toolmenu[] = array('icon'=>'listphoto.gif', 'title' => 'Все фотографии', 'link'=>'?view=components&do=config&id='.$id.'&opt=list_photos');

        $toolmenu[] = array('icon'=>'config.gif', 'title'=>$_LANG['AD_SETTINGS'], 'link'=>'?view=components&do=config&id='.$id.'&opt=config');

	}

	if($opt=='list_photos'){

	$toolmenu[] = array('icon'=>'edit.gif', 'title' => 'Редактировать выбранные', 'link' => "javascript:checkSel('?view=components&do=config&id=".$id."&opt=edit_photo&multiple=1');");
	$toolmenu[] = array('icon'=>'delete.gif', 'title' => 'Удалить выбранные', 'link' => "javascript:checkSel('?view=components&do=config&id=".$id."&opt=delete_photo&multiple=1');");
	$toolmenu[] = array('icon'=>'show.gif', 'title' => 'Публиковать выбранные', 'link' => "javascript:checkSel('?view=components&do=config&id=".$id."&opt=show_photo&multiple=1');");
	$toolmenu[] = array('icon' =>'hide.gif', 'title' => 'Скрыть выбранные', 'link' => "javascript:checkSel('?view=components&do=config&id=".$id."&opt=hide_photo&multiple=1');");

	}

	if (in_array($opt, array('config','add_album','edit_album','add_photo','edit_photo'))){

        $toolmenu[] = array('icon'=>'save.gif', 'title'=>$_LANG['SAVE'], 'link'=>'javascript:document.addform.submit();');
        $toolmenu[] = array('icon'=>'cancel.gif', 'title'=>$_LANG['CANCEL'], 'link'=>'?view=components&do=config&id='.$id);

	}

	cpToolMenu($toolmenu);

//=================================================================================================//
//=================================================================================================//

	if ($opt == 'config') {

        cpAddPathway($_LANG['AD_SETTINGS']);

        cpCheckWritable('/images/photos', 'folder');
		cpCheckWritable('/images/photos/medium', 'folder');
		cpCheckWritable('/images/photos/small', 'folder'); ?>

        <form action="index.php?view=components&amp;do=config&amp;id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" name="addform">
        <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
        <table width="" border="0" cellpadding="10" cellspacing="0" class="proptable" style="width: 550px;">
            <tr>
              <td width="300"><strong><?php echo $_LANG['AD_SHOW_LINKS_ORIGINAL']; ?>: </strong></td>
              <td width="">
                <label><input name="show_link" type="radio" value="1" <?php if ($cfg['link']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['YES']; ?></label>
                <label><input name="show_link" type="radio" value="0" <?php if (!$cfg['link']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['NO']; ?></label>
              </td>
            </tr>
            <tr>
              <td><strong><?php echo $_LANG['AD_RETAIN_BOOT']; ?>:</strong> </td>
              <td>
                  <label><input name="saveorig" type="radio" value="1" <?php if ($cfg['saveorig']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['YES']; ?> </label>
                  <label><input name="saveorig" type="radio" value="0" <?php if (!$cfg['saveorig']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['NO']; ?></label></td>
            </tr>
            <tr>
              <td><strong><?php echo $_LANG['AD_NUMBER_COLUMS']; ?>: </strong></td>
              <td><input class="uispin" name="maxcols" type="text" id="maxcols" size="5" value="<?php echo $cfg['maxcols'];?>"/> <?php echo $_LANG['AD_PIECES']; ?></td>
            </tr>
            <tr>
              <td valign="top"><strong><?php echo $_LANG['AD_ALBUM_SORT']; ?>: </strong></td>
              <td><select name="orderby" style="width:190px">
                <option value="title" <?php if($cfg['orderby']=='title') { echo 'selected'; } ?>><?php echo $_LANG['AD_BY_ALPHABET']; ?></option>
                <option value="pubdate" <?php if($cfg['orderby']=='pubdate') { echo 'selected'; } ?>><?php echo $_LANG['AD_BY_CALENDAR']; ?></option>
              </select>
                <select name="orderto" style="width:190px">
                  <option value="desc" <?php if($cfg['orderto']=='desc') { echo 'selected'; } ?>><?php echo $_LANG['AD_BY_DECREMENT']; ?></option>
                  <option value="asc" <?php if($cfg['orderto']=='asc') { echo 'selected'; } ?>><?php echo $_LANG['AD_BY_INCREMENT']; ?></option>
                </select></td>
            </tr>
            <tr>
              <td><strong><?php echo $_LANG['AD_SHOW_LINKS_LATEST']; ?>: </strong></td>
              <td>
                <label><input name="showlat" type="radio" value="1" <?php if ($cfg['showlat']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['YES']; ?></label>
                <label><input name="showlat" type="radio" value="0" <?php if (!$cfg['showlat']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['NO']; ?></label>
              </td>
            </tr>
            <tr>
              <td><strong><?php echo $_LANG['AD_SHOW_NUMBER']; ?>: </strong></td>
              <td>
                <input class="uispin" name="best_latest_perpage" type="text" size="5" value="<?php echo $cfg['best_latest_perpage']; ?>"/> <?php echo $_LANG['AD_PIECES']; ?>
              </td>
            </tr>
            <tr>
              <td><strong><?php echo $_LANG['AD_SHOW_NUMBER_COLUMN']; ?>: </strong></td>
              <td>
                <input class="uispin" name="best_latest_maxcols" type="text" size="5" value="<?php echo $cfg['best_latest_maxcols']; ?>"/> <?php echo $_LANG['AD_PIECES']; ?>
              </td>
            </tr>
            <tr>
              <td>
                  <strong><?php echo $_LANG['AD_ENABLE_WATERMARK']; ?></strong><br />
                  <span class="hinttext"><?php echo $_LANG['AD_WATERMARK_PHOTOS_HINT']; ?> "<a href="/images/watermark.png" target="_blank">/images/watermark.png</a>"</span></td>
              <td>
                <label><input name="watermark" type="radio" value="1" <?php if ($cfg['watermark']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['YES']; ?></label>
                <label><input name="watermark" type="radio" value="0" <?php if (!$cfg['watermark']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['NO']; ?>	</label>  				  </td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong style="margin:5px 0px 5px 0px"><?php echo $_LANG['AD_ROOT_METAKEYS']; ?></strong><br />
                    <div class="hinttext"><?php echo $_LANG['AD_FROM_COMMA'] ?><br /></div>
                    <textarea name="meta_keys" rows="2" style="width:580px"><?php echo $cfg['meta_keys'] ?></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong style="margin:5px 0px 5px 0px"><?php echo $_LANG['AD_ROOT_METADESC']; ?></strong><br />
                    <div class="hinttext"><?php echo $_LANG['SEO_METADESCR_HINT'] ?></div>
                    <textarea name="meta_desc" rows="4" style="width:580px"><?php echo $cfg['meta_desc'] ?></textarea>
                </td>
            </tr>
            <tr>
                 <td><strong><?php echo $_LANG['AD_USER_SEO_ACCESS']; ?> </strong></td>
                 <td>
                     <label><input name="seo_user_access" type="radio" value="1" <?php if ($cfg['seo_user_access']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['YES']; ?> </label>
                     <label><input name="seo_user_access" type="radio" value="0"  <?php if (!$cfg['seo_user_access']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['NO']; ?> </label>
                 </td>
             </tr>
          </table>
          <p>
            <input name="opt" type="hidden" value="saveconfig" />
            <input name="save" type="submit" id="save" value="<?php echo $_LANG['SAVE']; ?>" />
            <input name="back3" type="button" id="back3" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id;?>';"/>
          </p>
    </form>
		<?php
	}

//=================================================================================================//
//=================================================================================================//

	if ($opt == 'show_album'){
        $item_id = cmsCore::request('item_id', 'int', 0);
        $inDB->query("UPDATE cms_photo_albums SET published = 1 WHERE id = '$item_id'") ;
        echo '1'; exit;
	}

//=================================================================================================//
//=================================================================================================//

	if ($opt == 'hide_album'){
        $item_id = cmsCore::request('item_id', 'int', 0);
        $inDB->query("UPDATE cms_photo_albums SET published = 0 WHERE id = '$item_id'") ;
        echo '1'; exit;
	}

//=================================================================================================//
//=================================================================================================//

	if ($opt == 'submit_album'){

		if(!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $album['title']       = cmsCore::request('title', 'str', 'NO_TITLE');
        $album['description'] = cmsCore::request('description', 'str');
        $album['published']   = cmsCore::request('published', 'int');
        $album['showdate']    = cmsCore::request('showdate', 'int');
        $album['parent_id']   = cmsCore::request('parent_id', 'int');
        $album['showtype']    = cmsCore::request('showtype', 'str');
        $album['public']      = cmsCore::request('public', 'int');
        $album['orderby']     = cmsCore::request('orderby', 'str');
        $album['orderto']     = cmsCore::request('orderto', 'str');
        $album['perpage']     = cmsCore::request('perpage', 'int');
        $album['thumb1']      = cmsCore::request('thumb1', 'int');
        $album['thumb2']      = cmsCore::request('thumb2', 'int');
        $album['thumbsqr']    = cmsCore::request('thumbsqr', 'int');
        $album['cssprefix']   = cmsCore::request('cssprefix', 'str');
        $album['nav']         = cmsCore::request('nav', 'int');
        $album['uplimit']     = cmsCore::request('uplimit', 'int');
        $album['maxcols']     = cmsCore::request('maxcols', 'int');
        $album['orderform']   = cmsCore::request('orderform', 'int');
        $album['showtags']    = cmsCore::request('showtags', 'int');
        $album['bbcode']      = cmsCore::request('bbcode', 'int');
        $album['is_comments'] = cmsCore::request('is_comments', 'int');
        $album['meta_keys']   = cmsCore::request('meta_keys', 'str', '');
        $album['meta_desc']   = cmsCore::request('meta_desc', 'str', '');
        $album['pagetitle']   = cmsCore::request('pagetitle', 'str', '');

        $album = cmsCore::callEvent('ADD_ALBUM', $album);

		$inDB->addNsCategory('cms_photo_albums', $album);

		cmsCore::addSessionMessage($_LANG['AD_ALBUM'].' "'.stripslashes($album['title']).'" '.$_LANG['AD_ALBUM_CREATED'], 'success');

		cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_albums');

	}

//=================================================================================================//
//=================================================================================================//

	if($opt == 'delete_album'){

		if(cmsCore::inRequest('item_id')){

			$album = $inDB->getNsCategory('cms_photo_albums', cmsCore::request('item_id', 'int', 0));
			if (!$album) { cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_albums'); }

			cmsCore::addSessionMessage($_LANG['AD_ALBUM'].' "'.stripslashes($album['title']).'", '.$_LANG['AD_EMBEDED_PHOTOS_REMOVED'].'.', 'success');

			cmsPhoto::getInstance()->deleteAlbum($album['id'], '', $model->initUploadClass($album));

		}

		cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_albums');

	}

//=================================================================================================//
//=================================================================================================//

	if ($opt == 'update_album'){

		if(!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $item_id = cmsCore::request('item_id', 'int', 0);

        $old_album = $inDB->getNsCategory('cms_photo_albums', $item_id);
        if (!$old_album) { cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_albums'); }

        $album['title']         = cmsCore::request('title', 'str', 'NO_TITLE');
        $album['description']   = cmsCore::request('description', 'str', '');
        $album['published']     = cmsCore::request('published', 'int');
        $album['showdate']      = cmsCore::request('showdate', 'int');
        $album['parent_id']     = cmsCore::request('parent_id', 'int');
        $album['is_comments']   = cmsCore::request('is_comments', 'int');
        $album['showtype']      = cmsCore::request('showtype', 'str');
        $album['public']        = cmsCore::request('public', 'int');
        $album['orderby']       = cmsCore::request('orderby', 'str');
        $album['orderto']       = cmsCore::request('orderto', 'str');
        $album['perpage']       = cmsCore::request('perpage', 'int');
        $album['thumb1']        = cmsCore::request('thumb1', 'int');
        $album['thumb2']        = cmsCore::request('thumb2', 'int');
        $album['thumbsqr']      = cmsCore::request('thumbsqr', 'int');
        $album['cssprefix']     = cmsCore::request('cssprefix', 'str');
        $album['nav']           = cmsCore::request('nav', 'int');
        $album['uplimit']       = cmsCore::request('uplimit', 'int');
        $album['maxcols']       = cmsCore::request('maxcols', 'int');
        $album['orderform']     = cmsCore::request('orderform', 'int');
        $album['showtags']      = cmsCore::request('showtags', 'int');
        $album['bbcode']        = cmsCore::request('bbcode', 'int');
        $album['iconurl']       = cmsCore::request('iconurl', 'str');
        $album['meta_keys']     = cmsCore::request('meta_keys', 'str', '');
        $album['meta_desc']     = cmsCore::request('meta_desc', 'str', '');
        $album['pagetitle']     = cmsCore::request('pagetitle', 'str', '');

        // если сменили категорию
        if($old_album['parent_id'] != $album['parent_id']){
            // перемещаем ее в дереве
            $inCore->nestedSetsInit('cms_photo_albums')->MoveNode($item_id, $album['parent_id']);
        }

        $inDB->update('cms_photo_albums', $album, $item_id);
        cmsCore::addSessionMessage($_LANG['AD_ALBUM'].' "'.stripslashes($album['title']).'" '.$_LANG['AD_ALBUM_SAVED'].'.', 'success');
        cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_albums');

	}

//=================================================================================================//
//=================================================================================================//

	if ($opt == 'list_albums'){

		echo '<h3>'.$_LANG['AD_ALBUMS'].'</h3>';

		$fields[] = array('title'=>'id', 'field'=>'id', 'width'=>'30');
		$fields[] = array('title'=>$_LANG['TITLE'], 'field'=>'title', 'width'=>'', 'link'=>'?view=components&do=config&id='.$id.'&opt=edit_album&item_id=%id%');
		$fields[] = array('title'=>$_LANG['AD_ALBUM_COMMENTS'], 'field'=>'is_comments', 'width'=>'95', 'prc'=>'cpYesNo');
		$fields[] = array('title'=>$_LANG['AD_ADDING_USERS'], 'field'=>'public', 'width'=>'100', 'prc'=>'cpYesNo');
		$fields[] = array('title'=>$_LANG['AD_IS_PUBLISHED'], 'field'=>'published', 'width'=>'60', 'do'=>'opt', 'do_suffix'=>'_album');

        $actions[] = array('title'=>$_LANG['AD_VIEW_ONLINE'], 'icon'=>'search.gif', 'link'=>'/photos/%id%');
        $actions[] = array('title'=>$_LANG['EDIT'], 'icon'=>'edit.gif', 'link'=>'?view=components&do=config&id='.$id.'&opt=edit_album&item_id=%id%');
        $actions[] = array('title'=>$_LANG['DELETE'], 'icon'=>'delete.gif', 'confirm'=>$_LANG['AD_ALBUM_PHOTOS_DEL'], 'link'=>'?view=components&do=config&id='.$id.'&opt=delete_album&item_id=%id%');

		cpListTable('cms_photo_albums', $fields, $actions, 'parent_id>0 AND NSDiffer=""', 'NSLeft');

	}

//=================================================================================================//
//=================================================================================================//
	if ($opt == 'list_photos'){
		cpAddPathway('Фотографии', '?view=components&do=config&id='.$_REQUEST['id'].'&opt=list_photos');
		echo '<h3>Фотографии</h3>';
		
		//TABLE COLUMNS
		$fields = array();

		$fields[0]['title'] = 'id';		$fields[0]['field'] = 'id';		$fields[0]['width'] = '30';

		$fields[1]['title'] = 'Дата';		$fields[1]['field'] = 'pubdate';	$fields[1]['width'] = '80';		$fields[1]['filter'] = 15;
		$fields[1]['fdate'] = '%d/%m/%Y';

		$fields[2]['title'] = 'Название';	$fields[2]['field'] = 'title';		$fields[2]['width'] = '';
		$fields[2]['filter'] = 15;
		$fields[2]['link'] = '?view=components&do=config&id='.$_REQUEST['id'].'&opt=edit_photo&item_id=%id%';

		$fields[3]['title'] = 'Показ';		$fields[3]['field'] = 'published';	$fields[3]['width'] = '100';
                $fields[3]['do'] = 'opt';               $fields[3]['do_suffix'] = '_photo';

		$fields[4]['title'] = 'Просмотров';	$fields[4]['field'] = 'hits';		$fields[4]['width'] = '90';
		
		$fields[5]['title'] = 'Альбом';		$fields[5]['field'] = 'album_id';	$fields[5]['width'] = '250';
		$fields[5]['prc'] = 'cpPhotoAlbumById'; $fields[5]['filter'] = 1;               $fields[5]['filterlist'] = cpGetList('cms_photo_albums');
	
		//ACTIONS
		$actions = array();
		$actions[0]['title'] = 'Редактировать';
		$actions[0]['icon']  = 'edit.gif';
		$actions[0]['link']  = '?view=components&do=config&id='.$_REQUEST['id'].'&opt=edit_photo&item_id=%id%';

		$actions[1]['title'] = 'Удалить';
		$actions[1]['icon']  = 'delete.gif';
		$actions[1]['confirm'] = 'Удалить фотографию?';
		$actions[1]['link']  = '?view=components&do=config&id='.$_REQUEST['id'].'&opt=delete_photo&item_id=%id%';
				
		//Print table
		cpListTable('cms_photo_files', $fields, $actions, '', 'id DESC');		
	}

	if ($opt == 'add_album' || $opt == 'edit_album'){
        if ($opt=='add_album'){
             cpAddPathway($_LANG['AD_ALBUM_ADD']);
             echo '<h3>'.$_LANG['AD_ALBUM_ADD'].'</h3>';
        } else {

            $item_id = cmsCore::request('item_id', 'int', 0);

            $mod = $inDB->getNsCategory('cms_photo_albums', $item_id);

            cpAddPathway($_LANG['AD_ALBUM_EDIT']);
            echo '<h3>'.$_LANG['AD_ALBUM_EDIT'].' "'.$mod['title'].'"</h3>';

        }

        //DEFAULT VALUES
        if (!isset($mod['thumb1'])) { $mod['thumb1'] = 96; }
        if (!isset($mod['thumb2'])) { $mod['thumb2'] = 450; }
        if (!isset($mod['thumbsqr'])) { $mod['thumbsqr'] = 1; }
        if (!isset($mod['is_comments'])) { $mod['is_comments'] = 0; }
        if (!isset($mod['maxcols'])) { $mod['maxcols'] = 4; }
        if (!isset($mod['showtype'])) { $mod['showtype'] = 'lightbox'; }
        if (!isset($mod['perpage'])) { $mod['perpage'] = '20'; }
        if (!isset($mod['uplimit'])) { $mod['uplimit'] = 20; }
        if (!isset($mod['published'])) { $mod['published'] = 1; }
        if (!isset($mod['orderby'])) { $mod['orderby'] = 'pubdate'; }

		?>
		<script type="text/javascript">
        function showMapMarker(){
            var file = $('select[name=iconurl]').val();
            if(file){
                $('#marker_demo').attr('src', '/images/photos/small/'+file).fadeIn();
            } else {
                $('#marker_demo').hide();
            }

        }
        </script>

        <form id="addform" name="addform" method="post" action="index.php?view=components&do=config&id=<?php echo $id;?>">
        <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
        <table width="610" border="0" cellspacing="5" class="proptable">
            <tr>
                <td width="300"><?php echo $_LANG['AD_ALBUM_TITLE']; ?>:</td>
                <td><input name="title" type="text" id="title" style="width:280px" value="<?php echo htmlspecialchars($mod['title']); ?>"/></td>
            </tr>
            <tr>
                <td valign="top"><?php echo $_LANG['AD_ALBUM_PARENT']; ?>:</td>
                <td valign="top">
                    <?php $rootid = $inDB->get_field('cms_photo_albums', "parent_id=0 AND NSDiffer=''", 'id'); ?>
                    <select name="parent_id" size="8" id="parent_id" style="width:285px">
                        <option value="<?php echo $rootid; ?>" <?php if (@$mod['parent_id']==$rootid || !isset($mod['parent_id'])) { echo 'selected'; }?>><?php echo $_LANG['AD_ALBUM_ROOT']; ?></option>
                        <?php
                            if (isset($mod['parent_id'])){
                                echo $inCore->getListItemsNS('cms_photo_albums', $mod['parent_id']);
                            } else {
                                echo $inCore->getListItemsNS('cms_photo_albums');
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $_LANG['AD_ALBUM_POST']; ?>?</td>
                    <td>
                        <label><input name="published" type="radio" value="1" <?php if (@$mod['published']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['YES']; ?></label>
                        <label><input name="published" type="radio" value="0"  <?php if (@!$mod['published']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['NO']; ?></label>
                    </td>
            </tr>
            <tr>
                <td><?php echo $_LANG['AD_SHOW_DATES_COMMENTS']; ?>?</td>
                    <td>
                        <label><input name="showdate" type="radio" value="1" <?php if (@$mod['showdate']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['YES']; ?></label>
                        <label><input name="showdate" type="radio" value="0"  <?php if (@!$mod['showdate']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['NO']; ?></label>
                    </td>
            </tr>
            <tr>
                <td valign="top"><?php echo $_LANG['AD_SHOW_TAGS']; ?>:</td>
                <td valign="top">
                    <label><input name="showtags" type="radio" value="1" checked="checked" <?php if (@$mod['showtags']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['YES']; ?></label>
                    <label><input name="showtags" type="radio" value="0"  <?php if (@!$mod['showtags']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['NO']; ?></label>
                </td>
            </tr>
            <tr>
                <td valign="top"><?php echo $_LANG['AD_SHOW_CODE_FORUM'] ; ?>:</td>
                <td valign="top">
                    <label><input name="bbcode" type="radio" value="1" checked="checked" <?php if (@$mod['bbcode']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['YES']; ?></label>
                    <label><input name="bbcode" type="radio" value="0"  <?php if (@!$mod['bbcode']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['NO']; ?></label>
                </td>
            </tr>
            <tr>
                <td valign="top"><?php echo $_LANG['AD_COMMENTS_ALBUM']; ?>:</td>
                <td valign="top">
                    <label><input name="is_comments" type="radio" value="1" checked="checked" <?php if (@$mod['is_comments']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['YES']; ?></label>
                    <label><input name="is_comments" type="radio" value="0"  <?php if (@!$mod['is_comments']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['NO']; ?></label>
                </td>
            </tr>
            <tr>
                <td><?php echo $_LANG['AD_SORT_PHOTOS']; ?>:</td>
                <td>
                    <select name="orderby" id="orderby" style="width:285px">
                        <option value="title" <?php if(@$mod['orderby']=='title') { echo 'selected'; } ?>><?php echo $_LANG['AD_BY_ALPHABET']; ?></option>
                        <option value="pubdate" <?php if(@$mod['orderby']=='pubdate') { echo 'selected'; } ?>><?php echo $_LANG['AD_BY_CALENDAR']; ?></option>
                        <option value="rating" <?php if(@$mod['orderby']=='rating') { echo 'selected'; } ?>><?php echo $_LANG['AD_BY_RATING']; ?></option>
                        <option value="hits" <?php if(@$mod['orderby']=='hits') { echo 'selected'; } ?>><?php echo $_LANG['AD_BY_VIEWS']; ?></option>
                    </select>
                    <select name="orderto" id="orderto" style="width:285px">
                        <option value="desc" <?php if(@$mod['orderto']=='desc') { echo 'selected'; } ?>><?php echo $_LANG['AD_BY_DECREMENT']; ?></option>
                        <option value="asc" <?php if(@$mod['orderto']=='asc') { echo 'selected'; } ?>><?php echo $_LANG['AD_BY_INCREMENT']; ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $_LANG['AD_OUTPUT_PHOTOS']; ?>:</td>
                <td>
                    <select name="showtype" id="showtype" style="width:285px">
                        <option value="thumb" <?php if(@$mod['showtype']=='thumb') { echo 'selected'; } ?>><?php echo $_LANG['AD_GALLERY']; ?></option>
                        <option value="lightbox" <?php if(@$mod['showtype']=='lightbox') { echo 'selected'; } ?>><?php echo $_LANG['AD_GALLERY_LIGHTBOX']; ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $_LANG['AD_NUMBER_COLUMS_PHOTOS']; ?>:</td>
                <td>
                    <input class="uispin" name="maxcols" type="text" id="maxcols" size="5" value="<?php echo @$mod['maxcols'];?>"/> <?php echo $_LANG['AD_PIECES']; ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $_LANG['AD_ADD_PHOTOS_USERS']; ?>:</td>
                <td>
                    <select name="public" id="select" style="width:285px">
                        <option value="0" <?php if(@$mod['public']=='0') { echo 'selected'; } ?>><?php echo $_LANG['AD_PROCHBITED']; ?></option>
                        <option value="1" <?php if(@$mod['public']=='1') { echo 'selected'; } ?>><?php echo $_LANG['AD_FROM_PREMODERATION']; ?></option>
                        <option value="2" <?php if(@$mod['public']=='2') { echo 'selected'; } ?>><?php echo $_LANG['AD_WITHOUT_PREMODERATION']; ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $_LANG['AD_UPLOAD_MAX']; ?>:</td>
                <td>
                    <input class="uispin" name="uplimit" type="text" id="uplimit" size="5" value="<?php echo @$mod['uplimit'];?>"/> <?php echo $_LANG['AD_PIECES']; ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $_LANG['AD_FORM_SORTING']; ?>:</td>
                <td>
                    <label><input name="orderform" type="radio" value="1" checked="checked" <?php if (@$mod['orderform']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['SHOW']; ?></label>
                    <label><input name="orderform" type="radio" value="0"  <?php if (@!$mod['orderform']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['HIDE']; ?></label>
                </td>
            </tr>
            <tr>
                <td><?php echo $_LANG['AD_ALBUM_NAVIGATTING']; ?>:</td>
                <td>
                    <label><input name="nav" type="radio" value="1" <?php if (@$mod['nav']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['YES']; ?></label>
                    <label><input name="nav" type="radio" value="0"  <?php if (@!$mod['nav']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['NO']; ?></label>
                </td>
            </tr>
            <tr>
                <td><?php echo $_LANG['AD_CSS_PREFIX']; ?>:</td>
                <td><input name="cssprefix" type="text" id="cssprefix" size="10" value="<?php echo @$mod['cssprefix'];?>"/></td>
            </tr>
            <tr>
                <td><?php echo $_LANG['AD_PHOTOS_ON_PAGE']; ?>:</td>
                <td>
                    <input class="uispin" name="perpage" type="text" id="perpage" size="5" value="<?php echo @$mod['perpage'];?>"/> <?php echo $_LANG['AD_PIECES']; ?></td>
            </tr>
            <tr>
                <td><?php echo $_LANG['AD_WIDTH_SMALL_COPY']; ?>: </td>
                <td>
                    <table border="0" cellspacing="0" cellpadding="1">
                        <tr>
                            <td width="100" valign="middle">
                                <input class="uispin" name="thumb1" type="text" id="thumb1" size="3" value="<?php echo @$mod['thumb1'];?>"/> <?php echo $_LANG['AD_PX']; ?>.
                            </td>
                            <td width="100" align="center" valign="middle" style="background-color:#EBEBEB"><?php echo $_LANG['AD_PHOTOS_SQUARE']; ?>:</td>
                            <td width="115" align="center" valign="middle" style="background-color:#EBEBEB">
                                <label><input name="thumbsqr" type="radio" value="1" checked="checked" <?php if (@$mod['thumbsqr']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['YES']; ?> </label>
                                <label><input name="thumbsqr" type="radio" value="0"  <?php if (@!$mod['thumbsqr']) { echo 'checked="checked"'; } ?> /><?php echo $_LANG['NO']; ?> </label>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><?php echo $_LANG['AD_WIDTH_MIDDLE_COPY']; ?>: </td>
                <td>
                    <input class="uispin" name="thumb2" type="text" id="thumb2" size="3" value="<?php echo @$mod['thumb2'];?>"/> <?php echo $_LANG['AD_PX']; ?>.
                </td>
            </tr>
            <?php
                if ($opt=='edit_album'){ ?>
            <tr>
                <td valign="top"><?php echo $_LANG['AD_MINI_SKETCH']; ?>:<br />
                <?php if (!empty($mod['iconurl']) && file_exists(PATH.'/images/photos/small/'.$mod['iconurl'])){ ?>
                    <img id="marker_demo" src="/images/photos/small/<?php echo $mod['iconurl']; ?>">
                <?php  } else { ?>
                    <img id="marker_demo" src="/images/photos/no_image.png" style="display: none;">
                <?php  } ?>
                </td>
                <td valign="top">
                <?php if ($inDB->rows_count('cms_photo_files', 'album_id = '.$item_id.'')) { ?>
                    <select name="iconurl" id="iconurl" style="width:285px" onchange="showMapMarker()">
                        <?php
                            if (!empty($mod['iconurl']) && file_exists(PATH.'/images/photos/small/'.$mod['iconurl'])){
                                echo $inCore->getListItems('cms_photo_files', $mod['iconurl'], 'id', 'ASC', 'album_id = '.$item_id.' AND published = 1', 'file');
                            } else {
                                echo '<option value="" selected="selected">'.$_LANG['AD_MINI_SKETCH_CHOOSE'].'</option>';
                                echo $inCore->getListItems('cms_photo_files', '', 'id', 'ASC', 'album_id = '.$item_id.' AND published = 1', 'file');
                            }
                        ?>
                    </select>
                   <?php  } else { ?>
                        <?php echo $_LANG['AD_ALBUM_NO_PHOTOS']; ?>.
                   <?php  } ?>
                </td>
            </tr>
        <?php
            }
        ?>
        </table>
        <table border="0" width="610" cellspacing="5" class="proptable">
            <tr>
                <td>
                <div style="margin:5px 0px 5px 0px"><strong><?php echo $_LANG['AD_ALBUM_DESCR']; ?>:</strong></div>
                <textarea name="description" style="width:580px" rows="4"><?php echo @$mod['description']?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <strong style="margin:5px 0px 5px 0px"><?php echo $_LANG['SEO_PAGETITLE'] ?></strong><br />
                    <div class="hinttext"><?php echo $_LANG['SEO_PAGETITLE_HINT'] ?><br /></div>
                    <textarea name="pagetitle" rows="2" style="width:580px"><?php echo @$mod['pagetitle'] ?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <strong style="margin:5px 0px 5px 0px"><?php echo $_LANG['SEO_METAKEYS'] ?></strong><br />
                    <div class="hinttext"><?php echo $_LANG['AD_FROM_COMMA'] ?><br /></div>
                    <textarea name="meta_keys" rows="2" style="width:580px"><?php echo @$mod['meta_keys'] ?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <strong style="margin:5px 0px 5px 0px"><?php echo $_LANG['SEO_METADESCR'] ?></strong><br />
                    <div class="hinttext"><?php echo $_LANG['SEO_METADESCR_HINT'] ?></div>
                    <textarea name="meta_desc" rows="4" style="width:580px"><?php echo @$mod['meta_desc'] ?></textarea>
                </td>
            </tr>
        </table>

        <p>
            <input name="opt" type="hidden" id="opt" <?php if ($opt=='add_album') { echo 'value="submit_album"'; } else { echo 'value="update_album"'; } ?> />
            <input name="add_mod" type="submit" id="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
            <input name="back2" type="button" id="back2" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id; ?>';"/>
            <?php
                if ($opt=='edit_album'){
                    echo '<input name="item_id" type="hidden" value="'.$mod['id'].'" />';
                }
            ?>
        </p>
    </form>
<?php	}
	if ($opt == 'add_photo' || $opt == 'edit_photo'){	
            $inPhoto = cmsPhoto::getInstance();	
            if ($opt=='add_photo'){
                echo '<h3>Добавить фотографию</h3>';
            } else {
                
                if(isset($_REQUEST['multiple'])){				 
                    if (isset($_REQUEST['item'])){					
                        $_SESSION['editlist'] = $_REQUEST['item'];
                    } else {
                        echo '<p class="error">Нет выбранных объектов!</p>';
                        return;
                    }				 
                }
						
                $ostatok = '';
					
                if (isset($_SESSION['editlist'])){
                    $item_id = array_shift($_SESSION['editlist']);
                    if (sizeof($_SESSION['editlist'])==0) { unset($_SESSION['editlist']); } else 
                    { $ostatok = '(На очереди: '.sizeof($_SESSION['editlist']).')'; }
                } else { $item_id = cmsCore::request('item_id', 'int'); }
		
                $mod = $inPhoto->getPhoto($item_id);

                echo '<h3>'.$mod['title'].' '.$ostatok.'</h3>';
                cpAddPathway('Фотографии', '?view=components&do=config&id='.$id.'&opt=list_photos');
                cpAddPathway($mod['title'], '?view=components&do=config&id='.$id.'&opt=edit_photo&item_id='.$item_id);		
						
            }
		?>
		<?php cpCheckWritable('/images/photos', 'folder'); ?>
		<?php cpCheckWritable('/images/photos/medium', 'folder'); ?>
		<?php cpCheckWritable('/images/photos/small', 'folder'); ?>				
        <form action="index.php?view=components&do=config&id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" name="addform" id="addform">
        <table width="600" border="0" cellspacing="5" class="proptable">
        <tr>
            <td width="177">Название фотографии: </td>
            <td width="311"><input name="title" type="text" id="title" size="30" value="<?php echo htmlspecialchars($mod['title']);?>"/></td>
        </tr>
        <tr>
            <td valign="top">Фотоальбом:</td>
            <td valign="top">
                <?php if($opt=='add_photo' || ($opt=='edit_photo' && @$mod['NSDiffer']=='')){ ?>
                    <select name="album_id" size="8" id="album_id" style="width:250px">
                        <?php $rootid = $inDB->get_field('cms_photo_albums', "parent_id=0 AND NSDiffer=''", 'id'); ?>
                        <option value="<?php echo $rootid; ?>" <?php if (@$mod['album_id']==$rootid || !isset($mod['album_id'])) { echo 'selected'; }?>>-- Корневой альбом --</option>
                        <?php
                            if (isset($mod['album_id'])){
                               echo $inCore->getListItemsNS('cms_photo_albums', $mod['album_id']);
                            } else {
                               echo $inCore->getListItemsNS('cms_photo_albums');
                            }
                        ?>
                    </select>
                <?php } else {
                    $club['id']     = substr($mod['NSDiffer'], 4);
                    $club['title']  = $inDB->get_field('cms_clubs', "id={$club['id']}", 'title');
                ?><input type="hidden" name="album_id" value="<?php echo $mod['album_id']; ?>" />
                    Клуб <a href="index.php?view=components&do=config&id=23&opt=edit&item_id=<?php echo $club['id']; ?>"><?php echo $club['title'];?></a> &rarr; <?php echo $mod['album']; ?>
                <?php
                  }
                ?>
            </td>
        </tr>
        <tr>
            <td>Файл фотографии: </td>
            <td><?php if (@$mod['file']) {
                echo '<div><img src="/images/photos/small/'.$mod['file'].'" border="1" /></div>';
                echo '<div><a href="/images/photos/medium/'.$mod['file'].'">'.$mod['file'].'</a></div>';
            } ?>
                <input name="Filedata" type="file" id="picture" size="30" />
            </td>
        </tr>
        <tr>
            <td>Публиковать фотографию?</td>
            <td>
                <label><input name="published" type="radio" value="1" checked="checked" <?php if (@$mod['published'] || $opt=='add_photo') { echo 'checked="checked"'; } ?> /> Да</label>
                <label><input name="published" type="radio" value="0"  <?php if (@!$mod['published'] && $opt!='add_photo') { echo 'checked="checked"'; } ?> /> Нет</label>
            </td>
        </tr>
        <tr>
            <td>Показывать дату? </td>
            <td>
                <label><input name="showdate" type="radio" value="1" checked="checked" <?php if (@$mod['showdate'] || $opt=='add_photo') { echo 'checked="checked"'; } ?> /> Да</label>
                <label><input name="showdate" type="radio" value="0"  <?php if (@!$mod['showdate'] && $opt!='add_photo') { echo 'checked="checked"'; } ?> /> Нет</label>
            </td>
        </tr>
        
        <tr>
            <td>Комментарии для фотографии </td>
            <td>
                <label><input name="comments" type="radio" value="1" checked="checked" <?php if (@$mod['comments'] || $opt=='add_photo') { echo 'checked="checked"'; } ?> /> Да</label>
                <label><input name="comments" type="radio" value="0"  <?php if (@!$mod['comments'] && $opt!='add_photo') { echo 'checked="checked"'; } ?> /> Нет</label>
            </td>
        </tr>
        
        <?php if ($do=='add_photo'){ ?>
        <tr>
        <td>Cохранить оригинал: </td>
        <td><label><input name="saveorig" type="radio" value="1" checked="checked" />Да</label><label><input name="saveorig" type="radio" value="0"  />Нет</label></td>
        </tr>
        <?php } ?>
        <tr>
            <td valign="top">Теги фотографии: <br />
            <span class="hinttext">Ключевые слова, через запятую</span></td>
            <td valign="top"><input name="tags" type="text" id="tags" size="45" value="<?php if (isset($mod['id'])) { echo cmsTagLine('photo', $mod['id'], false); } ?>" /></td>
        </tr>
        
            <?php
            if(!isset($mod['user']) || @$mod['user']==1){
                echo '<tr><td valign="top">Описание фотографии:</td>';
                echo '<td valign="top"><textarea class="text-input" style="width:290px" rows="5" name="description">'.$mod['description'].'</textarea>';
                echo '</td></tr>';
            }
            ?>
        </table>

        <p>
            <input name="add_mod" type="submit" id="add_mod" <?php if ($opt=='add_photo') { echo 'value="Загрузить фото"'; } else { echo 'value="Сохранить фото"'; } ?> />
            <input name="back3" type="button" id="back3" value="Отмена" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id; ?>';"/>
            <input name="opt" type="hidden" id="opt" <?php if ($opt=='add_photo') { echo 'value="submit_photo"'; } else { echo 'value="update_photo"'; } ?> />
            <?php
            if ($opt=='edit_photo'){
                echo '<input name="item_id" type="hidden" value="'.$mod['id'].'" />';
            }
            ?>
        </p>
        </form>
<?php	}

//=================================================================================================//
//=================================================================================================//

    if ($opt == 'add_photo_multi'){
    /* Массовая загрузка переписана полностью - как на фронтенде. By soft-solution.ru */
    /* ШАГ 1. Описание фотографий */
	$inUser = cmsUser::getInstance();
        
        $mod = cmsUser::sessionGet('mod');
        if ($mod) { cmsUser::sessionDel('mod'); }

        $GLOBALS['cp_page_head'][] = '<script type="text/javascript" src="/includes/jquery/jquery.js"></script>';
        $GLOBALS['cp_page_head'][] = '<script type="text/javascript">
            $(document).ready(function() {
                $("#title").focus();
                $("#album_id").change(function () {
                    var cat_id = "";
                    //cat_id = 
                    $("#album_id option:selected").each(function () {
                        cat_id = $(this).val();
                    });
                    if(cat_id != 0) {
                        $("#addform").attr("action", "/admin/index.php?view=components&do=config&id='.$_REQUEST['id'].'&opt=add_photo_multi_step2&id_album="+cat_id+"");
                    } else {
                        $("#addform").attr("action", "");
}
                })
                .change();
            });
        </script>';

        echo '<h3>Массовая загрузка фото. Шаг 1: Описание фотографий.</h3>';
        cpAddPathway('Массовая загрузка фото. Шаг 1: Описание фотографий.', $_SERVER['REQUEST_URI']); ?>

        <form action="" method="post" enctype="multipart/form-data" name="addform" id="addform">
            <table width="600" border="0" cellspacing="5" class="proptable">
                <tr>
                    <td colspan="2"><div class="usr_photos_notice">Обратите внимание: если вы укажете название фотографий, то оно будет одно на все загруженные с порядковым номером в конце; если поле оставите пустым, то название фотографии будет браться автоматически из имени файла, исключая расширение.</div></td>
                </tr>
            <tr>
             <td width="177">Названия фотографий: </td>
             <td width="311"><input name="title" type="text" id="title" class="text-input" maxlength="250" value="" style="width:350px;" /></td>
         </tr>
         <tr>
             <td valign="top">Фотоальбом:</td>
             <td valign="top"><select name="album_id" size="8" id="album_id" style="width:250px">
                     <?php  //FIND MENU ROOT
                        $rootid = $inDB->get_field('cms_photo_albums', 'parent_id=0', 'id');
                     ?>
                     <option value="<?php echo $rootid?>" <?php if (@$mod['album_id']==$rootid || !isset($mod['album_id'])) { echo 'selected'; }?>>-- Корневой альбом --</option>
                     <?php if (isset($mod['album_id'])){
                         echo $inCore->getListItemsNS('cms_photo_albums', $mod['album_id']);
                     } else {
                         echo $inCore->getListItemsNS('cms_photo_albums');
                     }
                     ?>
             </select></td>
         </tr>
            <tr>
                 <td>Публиковать фотографии?</td>
                 <td><label><input name="published" type="radio" value="1" checked="checked" /> Да </label>
                     <label><input name="published" type="radio" value="0" /> Нет </label>
                 </td>
             </tr>
             <tr>
                 <td>Показывать даты? </td>
                 <td><label><input name="showdate" type="radio" value="1" checked="checked" <?php if (@$mod['showdate']) { echo 'checked="checked"'; } ?> /> Да </label>
                     <label><input name="showdate" type="radio" value="0" /> Нет </label>
                 </td>
             </tr>
            <tr>
                 <td>Комментарии разрешены: </td>
                 <td><label><input name="comments" type="radio" value="1" checked="checked" /> Да</label>
                     <label><input name="comments" type="radio" value="0"> Нет </label>
                 </td>
             </tr>
             <tr>
                <td valign="top" id="text_desc">Описание фотографий: </td>
                <td valign="top">
                    <textarea name="description" style="width:350px;" rows="5" id="description"></textarea>
                </td>
            </tr>
            <tr>
                <td valign="top">Теги фотографий: <br />
                <span class="hinttext">Ключевые слова, через запятую</span></td>
                <td valign="top"><input name="tags" type="text" id="tags" style="width:350px;" /></td>
            </tr>
        </table>
        <p>
            <input type="submit" name="submit" id="text_subm" value="Перейти к загрузке" />
            <input name="back3" type="button" id="cancel_btn" value="Отмена" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id; ?>';"/>
        </p>
    </form>

    <?php }
    if ($opt == 'add_photo_multi_step2'){
        
        $inUser = cmsUser::getInstance();
        
        $album_id    = $inCore->request('album_id', 'int', 100);
        $album_title = $inDB->get_field('cms_photo_albums', 'id='.$album_id, 'title');
        
        $mod    = array();
        $mod['published']   = $inCore->request('published', 'int', 1);
        $mod['showdate']    = $inCore->request('showdate', 'int', 1);
        $mod['title']       = $inCore->request('title', 'str', '');
        $mod['description'] = $inCore->request('description', 'str');
        $mod['tags']        = $inCore->request('tags', 'str');
        $mod['is_multi']    = 1;
        $mod['comments']    = $inCore->request('comments', 'int', 1);
        
        cmsUser::sessionPut('mod', $mod);
        
        echo '<h3>Массовая загрузка фото. Шаг 2: Загрузка фотографий.</h3>';
        cpAddPathway('Массовая загрузка фото. Шаг 2: Загрузка фотографий.', $_SERVER['REQUEST_URI']); 
        
        $GLOBALS['cp_page_head'][] = '<script type="text/javascript" src="/includes/jquery/jquery.js"></script>';
        
        $GLOBALS['cp_page_head'][] = '<script type="text/javascript" src="/includes/swfupload/swfupload.js"></script>';
        $GLOBALS['cp_page_head'][] = '<script type="text/javascript" src="/includes/swfupload/swfupload.queue.js"></script>';
        $GLOBALS['cp_page_head'][] = '<script type="text/javascript" src="/includes/swfupload/fileprogress.js"></script>';
        $GLOBALS['cp_page_head'][] = '<script type="text/javascript" src="/includes/swfupload/handlers.js"></script>';
        $GLOBALS['cp_page_head'][] = '<link href="/includes/swfupload/swfupload.css" rel="stylesheet" type="text/css" />';
        
        $sess_id  = session_id();
        ?>

        <h3>Внимание!!! Фотографии будут загружаться в фотоальбом "<u><?php echo $album_title; ?></u>"</h3>

<script type="text/javascript">
    var swfu;
    var uploadedCount = 0;

    window.onload = function() {
        var settings = {
            flash_url : "/includes/swfupload/swfupload.swf",
            upload_url: "/components/photos/ajax/upload_photo.php",
            post_params: {"sess_id" :"<?php echo $sess_id; ?>", "album_id" : "<?php echo $album_id; ?>"},
            file_size_limit : "20 MB",
            file_types : "*.jpg;*.png;*.gif;*.jpeg;*.JPG;*.PNG;*.GIF;*.JPEG",
            file_types_description : "Фотографии",
            file_upload_limit : 100,
            file_queue_limit : 0,
            custom_settings : {
                progressTarget : "fsUploadProgress",
                cancelButtonId : "btnCancel"
            },
            debug: false,

            // Button settings
            button_image_url: "/includes/swfupload/uploadbtn199x36.png",
            button_width: "199",
            button_height: "36",
            button_placeholder_id: "spanButtonPlaceHolder",

            // The event handler functions are defined in handlers.js
            file_queued_handler : fileQueued,
            file_queue_error_handler : fileQueueError,
            file_dialog_complete_handler : fileDialogComplete,
            upload_start_handler : uploadStart,
            upload_progress_handler : uploadProgress,
            upload_error_handler : uploadError,
            upload_success_handler : uploadSuccess,
            upload_complete_handler : uploadComplete,
            queue_complete_handler : queueComplete	// Queue plugin event
        };

        swfu = new SWFUpload(settings);
    };

    function queueComplete(numFilesUploaded) {
        if (numFilesUploaded>0){
            uploadedCount += numFilesUploaded;
            $('#divStatus').show();
            $('#continue').show();
            $("#files_count").html(uploadedCount);
        }
    }
</script>

        <form id="usr_photos_upload_form" action="" method="post" enctype="multipart/form-data">
            <div class="fieldset flash" id="fsUploadProgress" style="display:none">
                <span class="legend">Очередь загрузки</span>
            </div>
            <div>
                <span id="spanButtonPlaceHolder"></span>
                <input id="btnCancel" type="button" value="Отменить все" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 36px;" />
            </div>
            <div id="divStatus" style="display:none">
                Загружено <span id="files_count"><strong>0</strong></span> Фото.
                <a href="index.php?view=components&do=config&id=<?php echo $id; ?>&opt=list_photos" id="continue">Продолжить</a>
            </div>
        </form>

        <?php
    }
    
            //=================================================================================================//
//=================================================================================================//
	if ($opt == 'show_photo'){
		if (!isset($_REQUEST['item'])){
                    if (isset($_REQUEST['item_id'])){
                        dbShow('cms_photo_files', cmsCore::request('item_id', 'int'));
                    }
                    echo '1'; exit;
		} else {
                    dbShowList('cms_photo_files', $_REQUEST['item']);				
                    header('location:'.$_SERVER['HTTP_REFERER']);				
		}			
	}

//=================================================================================================//
//=================================================================================================//

	if ($opt == 'hide_photo'){
		if (!isset($_REQUEST['item'])){
                    
                    if (isset($_REQUEST['item_id'])){
                        dbHide('cms_photo_files', cmsCore::request('item_id', 'int'));
                    }
                    echo '1'; exit;
                    
		} else {
                    dbHideList('cms_photo_files', $_REQUEST['item']);				
                    header('location:'.$_SERVER['HTTP_REFERER']);			
		}			
	}

//=================================================================================================//
//=================================================================================================//

	if ($opt == 'submit_photo'){
            $inPhoto = cmsPhoto::getInstance();	
            $photo = array();
            
            $photo['album_id']      = $inCore->request('album_id', 'int', 0);
            $photo['title']         = $inCore->request('title', 'str');
            $photo['description']   = $inCore->request('description', 'str');
            $photo['published']     = $inCore->request('published', 'int', 1);
            $photo['showdate']      = $inCore->request('showdate', 'int', 1);
            $photo['tags']          = $inCore->request('tags', 'str');
            $photo['comments']      = $inCore->request('comments', 'int', 1);
            
            $album = $inDB->getNsCategory('cms_photo_albums', $photo['album_id']);
            $album = cmsCore::callEvent('GET_PHOTO_ALBUM', $album);
            
            // Загружаем фото
            $file = $model->initUploadClass($album)->uploadPhoto();
            
            if ($file) {
                
                //for upload photo only
                $last_id = $inDB->get_field('cms_photo_files', 'published=1 ORDER BY id DESC', 'id');

		$photo['file']      = $file['filename'];
		$photo['title']     = $photo['title'] ? $photo['title'] . $last_id : $file['realfile'];
		$photo['owner']     = 'photos';
		$photo['user_id']   = $inUser->id;
		$photo_id = $inPhoto->addPhoto($photo);
                
                if($photo['published']){

                    $description = '<a href="/photos/photo'.$photo_id.'.html" class="act_photo"><img border="0" src="/images/photos/small/'.$photo['file'].'" /></a>';

                    cmsActions::log('add_photo', array(
                                'object' => $photo['title'],
                                'object_url' => '/photos/photo'.$photo_id.'.html',
                                'object_id' => $photo_id,
                                'target' => $album['title'],
                                'target_id' => $album['id'],
                                'target_url' => '/photos/'.$album['id'],
                                'description' => $description
                    ));
		}
                
            } else {
                $msg = 'Ошибка загрузки фотографии!';
                cmsCore::addSessionMessage($msg, 'error');
            }
			
            $inCore->redirect('?view=components&do=config&opt=list_photos&id='.$_REQUEST['id']);
	}
        
//=================================================================================================//
//=================================================================================================//

	if ($opt == 'update_photo'){
            $inPhoto = cmsPhoto::getInstance();	
            if($inCore->inRequest('item_id')) {
                $item_id = $inCore->request('item_id', 'int');
                
                $photo = cmsCore::callEvent('GET_PHOTO', $inPhoto->getPhoto($item_id));

                $mod = array();
                $mod['album_id']       = $inCore->request('album_id', 'int');
                $mod['title']          = $inCore->request('title', 'str');
		$mod['title']          = $mod['title'] ? $mod['title'] : $photo['title'];
		$mod['description']    = cmsCore::request('description', 'str', '');
		$mod['tags']           = cmsCore::request('tags', 'str', '');
		$mod['comments']       = $inCore->request('comments', 'int', 1);
                $mod['published']      = $inCore->request('published', 'int');
                $mod['showdate']       = $inCore->request('showdate', 'int');

		$file = $model->initUploadClass($inDB->getNsCategory('cms_photo_albums', $mod['album_id']))->uploadPhoto($photo['file']);
		$mod['file'] = $file['filename'] ? $file['filename'] : $photo['file'];

		$inPhoto->updatePhoto($mod, $photo['id']);

		$description = '<a href="/photos/photo'.$photo['id'].'.html" class="act_photo"><img border="0" src="/images/photos/small/'.$mod['file'].'" /></a>';

		cmsActions::updateLog('add_photo', array('object' => $mod['title'], 'description' => $description), $photo['id']);

		cmsCore::addSessionMessage('Фото сохранено', 'success');

            }

            if (!isset($_SESSION['editlist']) || @sizeof($_SESSION['editlist'])==0){
                    $inCore->redirect('?view=components&do=config&id='.$id.'&opt=list_photos');
            } else {
                    $inCore->redirect('?view=components&do=config&id='.$id.'&opt=edit_photo');
            }
	}

//=================================================================================================//
//=================================================================================================//

	if ($opt == 'submit_photo_multi'){	
            echo '<h3>Загрузка файлов завершена</h3>';

            $photo['album_id']     = $inCore->request('album_id', 'int');			
            $photo['description']  = $inCore->request('description', 'html');
            $photo['description']  = $inDB->escape_string($photo['description']);
            $photo['published']    = $inCore->request('published', 'int');
            $photo['showdate']     = $inCore->request('showdate', 'int');
            $photo['tags']         = $inCore->request('tags', 'str');
			
            $uploaddir             = PATH.'/images/photos/';

            $album                 = $model->getAlbumThumbsData($photo['album_id']);
            $titlemode             = $inCore->request('titlemode', 'str');

            $loaded_files = array();

            $list_files = array();

            foreach($_FILES['upfile'] as $key=>$value) {
                foreach($value as $k=>$v) { $list_files['upfile'.$k][$key] = $v; }
            }

            foreach ($list_files as $key=>$data_array) {
					$error = $data_array['error'];
					if ($error == UPLOAD_ERR_OK) {
						
						$realfile = $data_array['name'];
						$tmp_name = $data_array['tmp_name'];
			
						$lid = dbGetFields('cms_photo_files', 'id>0', 'id', 'id DESC');
						$lastid = $lid['id']+1;	
						$filename = md5($realfile . '-' . $inUser->id . '-' . time()).'.jpg';
			
						$uploadfile = $uploaddir . $realfile;
						$uploadphoto = $uploaddir . $filename;
						$uploadthumb = $uploaddir . 'small/' . $filename;
						$uploadthumb2 = $uploaddir . 'medium/' . $filename;

                        $photo['filename'] = $filename;
						
                        if (move_uploaded_file($tmp_name, $uploadphoto)){
                            $loaded_files[] = $realfile;

                            @img_resize($uploadphoto, $uploadthumb, $album['thumb1'], $album['thumb1'], $album['thumbsqr']);
                            @img_resize($uploadphoto, $uploadthumb2, $album['thumb2'], $album['thumb2'], false, $cfg['watermark']);
                            if ($cfg['watermark']) { @img_add_watermark($uploadphoto);	}
                            
                            if (@!$inCore->inRequest('saveorig')){ @unlink($uploadphoto); }

                            if($titlemode == 'number'){
                                $photo['title'] = 'Фото #'.sizeof($loaded_files);
                            } else {
                                $photo['title'] = $realfile;
                            }

                            $model->addPhoto($photo);
                        }
					}
				}
					
				echo '<div style="padding:20px">';	
                    if (sizeof($loaded_files)){
                        echo '<div><strong>Загруженные файлы:</strong></div>';
                        echo '<ul>';
                            foreach($loaded_files as $k=>$val){
                                echo '<li>'.$val.'</li>';
                            }
                        echo '</ul>';
                    } else {
                        echo '<div style="color:red">Ни один файл не был загружен. Может файлы слишком большие?</div>';
                        echo '<div style="color:red">Имена файлов не должны содержать пробелов и русских букв.</div>';
                    }
                    echo '<div><a href="/admin/index.php?view=components&do=config&opt=list_photos&id='.$_REQUEST['id'].'">Продолжить</a> &rarr;</div>';
				echo '</div>';
	}	  

//=================================================================================================//
//=================================================================================================//

        if($opt == 'delete_photo'){
            $inPhoto = cmsPhoto::getInstance();	
            if (!isset($_REQUEST['item'])){

                $item_id = $inCore->request('item_id', 'int');
                if ($item_id > 0){
                    $photo = cmsCore::callEvent('GET_PHOTO', $inPhoto->getPhoto($item_id));
                    $inPhoto->deletePhoto($photo, $model->initUploadClass($inDB->getNsCategory('cms_photo_albums', $photo['album_id'])));
                    cmsCore::addSessionMessage($_LANG['PHOTO_DELETED'], 'success');
                }

            } else {
                foreach($_REQUEST['item'] as $key=>$item_id){
                    $photo = cmsCore::callEvent('GET_PHOTO', $inPhoto->getPhoto($item_id));
                    $inPhoto->deletePhoto($photo, $model->initUploadClass($inDB->getNsCategory('cms_photo_albums', $photo['album_id'])));
                }
                cmsCore::addSessionMessage('Фотографии удалены', 'success');
            }

            $inCore->redirect('?view=components&do=config&id='.$id.'&opt=list_photos');
            //cmsUser::clearCsrfToken();
        }
        
//=================================================================================================//
//=================================================================================================//
function cpPhotoAlbumById($id){
    
    $inDB = cmsDatabase::getInstance();
    $result = $inDB->query("SELECT title FROM cms_photo_albums WHERE id = $id");

	if ($inDB->num_rows($result)) {
            $cat = $inDB->fetch_assoc($result);
		return '<a href="index.php?view=components&do=config&id='.$_REQUEST['id'].'&opt=edit_album&item_id='.$id.'">'.$cat['title'].'</a> ('.$id.')';
	} else { return '--'; }

} ?>