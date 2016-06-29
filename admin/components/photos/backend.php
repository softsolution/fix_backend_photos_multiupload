<?php
if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }
/******************************************************************************/
//                                                                            //
//                             InstantCMS v1.10                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2012                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

	function viewAct($value){
		if ($value) {
			$value = '<span style="color:green;">да</span>';
		} else {
			$value = '<span style="color:red;">Нет</span>';
		}
		return $value;
	}

    $cfg = $inCore->loadComponentConfig('photos');

    $inDB = cmsDatabase::getInstance();
    
    cmsCore::loadClass('photo');
    $inPhoto = cmsPhoto::getInstance();
    cmsCore::loadModel('photos');
    $model = new cms_model_photos();

    $opt = cmsCore::request('opt', 'str', 'list_albums');
	$id  = cmsCore::request('id', 'int');

	cpAddPathway('Фотогалерея', '?view=components&do=config&id='.$id);
	echo '<h3>Фотогалерея</h3>';

//=================================================================================================//
//=================================================================================================//

	$toolmenu = array();

	if($opt=='saveconfig'){

		if(!cmsCore::validateForm()) { cmsCore::error404(); }

		$cfg = array();
		$cfg['link']        = cmsCore::request('show_link', 'int', 0);
		$cfg['saveorig']    = cmsCore::request('saveorig', 'int', 0);
		$cfg['maxcols']     = cmsCore::request('maxcols', 'int', 0);
		$cfg['orderby']     = cmsCore::request('orderby', 'str', '');
		$cfg['orderto']     = cmsCore::request('orderto', 'str', '');
		$cfg['showlat']     = cmsCore::request('showlat', 'int', 0);
		$cfg['watermark']   = cmsCore::request('watermark', 'int', 0);
		$cfg['best_latest_perpage'] = cmsCore::request('best_latest_perpage', 'int', 0);
		$cfg['best_latest_maxcols'] = cmsCore::request('best_latest_maxcols', 'int', 0);

		$inCore->saveComponentConfig('photos', $cfg);

		cmsCore::addSessionMessage('Настройки успешно сохранены', 'success');

        cmsUser::clearCsrfToken();

		cmsCore::redirectBack();

	}

//=================================================================================================//
//=================================================================================================//

	//if ($opt=='list_albums'){
        if ($opt=='list_photos' || $opt=='list_albums'){

		$toolmenu[0]['icon'] = 'newfolder.gif';
		$toolmenu[0]['title'] = 'Новый альбом';
		$toolmenu[0]['link'] = '?view=components&do=config&id='.$id.'&opt=add_album';
                
                $toolmenu[1]['icon'] = 'newphoto.gif';
		$toolmenu[1]['title'] = 'Новая фотография';
		$toolmenu[1]['link'] = '?view=components&do=config&id='.$id.'&opt=add_photo';

		$toolmenu[2]['icon'] = 'newphotomulti.gif';
		$toolmenu[2]['title'] = 'Массовая загрузка фото';
		$toolmenu[2]['link'] = '?view=components&do=config&id='.$id.'&opt=add_photo_multi';

		$toolmenu[3]['icon'] = 'folders.gif';
		$toolmenu[3]['title'] = 'Фотоальбомы';
		$toolmenu[3]['link'] = '?view=components&do=config&id='.$id.'&opt=list_albums';
                
                $toolmenu[4]['icon'] = 'listphoto.gif';
		$toolmenu[4]['title'] = 'Все фотографии';
		$toolmenu[4]['link'] = '?view=components&do=config&id='.$id.'&opt=list_photos';

		$toolmenu[5]['icon'] = 'config.gif';
		$toolmenu[5]['title'] = 'Настройки';
		$toolmenu[5]['link'] = '?view=components&do=config&id='.$id.'&opt=config';

	}
        
//=================================================================================================//
//=================================================================================================//

	if($opt=='list_photos'){

		$toolmenu[11]['icon'] = 'edit.gif';
		$toolmenu[11]['title'] = 'Редактировать выбранные';
		$toolmenu[11]['link'] = "javascript:checkSel('?view=components&do=config&id=".$id."&opt=edit_photo&multiple=1');";

		$toolmenu[12]['icon'] = 'delete.gif';
		$toolmenu[12]['title'] = 'Удалить выбранные';
		$toolmenu[12]['link'] = "javascript:checkSel('?view=components&do=config&id=".$id."&opt=delete_photo&multiple=1');";

		$toolmenu[13]['icon'] = 'show.gif';
		$toolmenu[13]['title'] = 'Публиковать выбранные';
		$toolmenu[13]['link'] = "javascript:checkSel('?view=components&do=config&id=".$id."&opt=show_photo&multiple=1');";

		$toolmenu[14]['icon'] = 'hide.gif';
		$toolmenu[14]['title'] = 'Скрыть выбранные';
		$toolmenu[14]['link'] = "javascript:checkSel('?view=components&do=config&id=".$id."&opt=hide_photo&multiple=1');";

	}

//=================================================================================================//
//=================================================================================================//

	//if (in_array($opt, array('config','add_album','edit_album'))){
        if (in_array($opt, array('config','add_album','edit_album','add_photo','edit_photo'))){

		$toolmenu[20]['icon'] = 'save.gif';
		$toolmenu[20]['title'] = 'Сохранить';
		$toolmenu[20]['link'] = 'javascript:document.addform.submit();';

		$toolmenu[21]['icon'] = 'cancel.gif';
		$toolmenu[21]['title'] = 'Отмена';
		$toolmenu[21]['link'] = '?view=components&do=config&id='.$id;

	}

	cpToolMenu($toolmenu);
        
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
            cmsUser::clearCsrfToken();
        }

//=================================================================================================//
//=================================================================================================//

	if ($opt == 'config') {

        cpAddPathway('Настройки', '?view=components&do=config&id='.$id.'&opt=config');

		?>
		<?php cpCheckWritable('/images/photos', 'folder'); ?>
		<?php cpCheckWritable('/images/photos/medium', 'folder'); ?>
		<?php cpCheckWritable('/images/photos/small', 'folder'); ?>

        <form action="index.php?view=components&amp;do=config&amp;id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" name="optform">
        <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
          <table width="" border="0" cellpadding="10" cellspacing="0" class="proptable">
            <tr>
              <td width="300"><strong>Показывать ссылки на оригинал: </strong></td>
              <td width="250">
                <label><input name="show_link" type="radio" value="1" <?php if ($cfg['link']) { echo 'checked="checked"'; } ?>/> Да</label>
                <label><input name="show_link" type="radio" value="0" <?php if (!$cfg['link']) { echo 'checked="checked"'; } ?>/> Нет</label>
              </td>
            </tr>
            <tr>
              <td><strong>Сохранять оригиналы при загрузке<br />
              фотографий пользователями:</strong> </td>
              <td>
                  <label><input name="saveorig" type="radio" value="1" <?php if ($cfg['saveorig']) { echo 'checked="checked"'; } ?>/> Да</label>
                  <label><input name="saveorig" type="radio" value="0" <?php if (!$cfg['saveorig']) { echo 'checked="checked"'; } ?>/> Нет	</label>			  </td>
            </tr>
            <tr>
              <td><strong>Количество колонок для<br />вывода списка альбомов: </strong></td>
              <td><input name="maxcols" type="text" id="maxcols" size="5" value="<?php echo $cfg['maxcols'];?>"/> шт</td>
            </tr>
            <tr>
              <td valign="top"><strong>Сортировать список альбомов: </strong></td>
              <td><select name="orderby" style="width:190px">
                <option value="title" <?php if($cfg['orderby']=='title') { echo 'selected'; } ?>>По алфавиту</option>
                <option value="pubdate" <?php if($cfg['orderby']=='pubdate') { echo 'selected'; } ?>>По дате</option>
              </select>
                <select name="orderto" style="width:190px">
                  <option value="desc" <?php if($cfg['orderto']=='desc') { echo 'selected'; } ?>>по убыванию</option>
                  <option value="asc" <?php if($cfg['orderto']=='asc') { echo 'selected'; } ?>>по возрастанию</option>
                </select></td>
            </tr>
            <tr>
              <td><strong>Показывать ссылки на последние и лучшие фото: </strong></td>
              <td>
                <label><input name="showlat" type="radio" value="1" <?php if ($cfg['showlat']) { echo 'checked="checked"'; } ?>/> Да</label>
                <label><input name="showlat" type="radio" value="0" <?php if (!$cfg['showlat']) { echo 'checked="checked"'; } ?>/> Нет</label>
              </td>
            </tr>
            <tr>
              <td><strong>Количество последних/лучших фото на странице: </strong></td>
              <td>
                <input name="best_latest_perpage" type="text" size="5" value="<?php echo $cfg['best_latest_perpage']; ?>"/> шт
              </td>
            </tr>
            <tr>
              <td><strong>Количество колонок последних/лучших фото: </strong></td>
              <td>
                <input name="best_latest_maxcols" type="text" size="5" value="<?php echo $cfg['best_latest_maxcols']; ?>"/> шт
              </td>
            </tr>
            <tr>
              <td>
                  <strong>Наносить водяной знак:</strong><br />
                  <span class="hinttext">Если включено, то на все загружаемые фотографии будет наносится изображение из файла "<a href="/images/watermark.png" target="_blank">/images/watermark.png</a>"</span></td>
              <td>
                <label><input name="watermark" type="radio" value="1" <?php if ($cfg['watermark']) { echo 'checked="checked"'; } ?>/> Да</label>
                <label><input name="watermark" type="radio" value="0" <?php if (!$cfg['watermark']) { echo 'checked="checked"'; } ?>/> Нет	</label>  				  </td>
            </tr>
          </table>
          <p>
            <input name="opt" type="hidden" value="saveconfig" />
            <input name="save" type="submit" id="save" value="Сохранить" />
          </p>
    </form>
		<?php
	}

//=================================================================================================//
//=================================================================================================//

	if ($opt == 'show_album'){
		if(isset($_REQUEST['item_id'])) {
			$item_id = cmsCore::request('item_id', 'int');
			$sql = "UPDATE cms_photo_albums SET published = 1 WHERE id = '$item_id'";
			$inDB->query($sql) ;
			echo '1'; exit;
		}
	}

//=================================================================================================//
//=================================================================================================//

	if ($opt == 'hide_album'){
		if(isset($_REQUEST['item_id'])) {
			$item_id = cmsCore::request('item_id', 'int');
			$sql = "UPDATE cms_photo_albums SET published = 0 WHERE id = '$item_id'";
			$inDB->query($sql) ;
			echo '1'; exit;
		}
	}

//=================================================================================================//
//=================================================================================================//

	if ($opt == 'submit_album'){

		if(!cmsCore::validateForm()) { cmsCore::error404(); }

        $album['title']         = cmsCore::request('title', 'str');
		if(!$album['title']) { $album['title'] = 'Альбом без названия'; }
		$album['description']   = cmsCore::request('description', 'str');
		$album['published']     = cmsCore::request('published', 'int');
		$album['showdate']      = cmsCore::request('showdate', 'int');
		$album['parent_id']     = cmsCore::request('parent_id', 'int');
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
        $album['is_comments']   = cmsCore::request('is_comments', 'int');

		$album  = cmsCore::callEvent('ADD_ALBUM', $album);

		$inDB->addNsCategory('cms_photo_albums', $album);

		cmsCore::addSessionMessage('Альбом "'.stripslashes($album['title']).'" успешно создан', 'success');

        cmsUser::clearCsrfToken();

		cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_albums');

	}

//=================================================================================================//
//=================================================================================================//

	if($opt == 'delete_album'){

		if(cmsCore::inRequest('item_id')){

			$album = $inDB->getNsCategory('cms_photo_albums', cmsCore::request('item_id', 'int'));
			if (!$album) { cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_albums'); }

			cmsCore::addSessionMessage('Альбом "'.stripslashes($album['title']).'", вложенные в него и все фотографии в них удалены.', 'success');

			cmsPhoto::getInstance()->deleteAlbum($album['id'], '', $model->initUploadClass($album));

		}

		cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_albums');

	}

//=================================================================================================//
//=================================================================================================//

	if ($opt == 'update_album'){

		if(!cmsCore::validateForm()) { cmsCore::error404(); }

		if(cmsCore::inRequest('item_id')) {

			$item_id = cmsCore::request('item_id', 'int');

			$old_album = $inDB->getNsCategory('cms_photo_albums', $item_id);
			if (!$old_album) { cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_albums'); }

            $album['title']         = cmsCore::request('title', 'str');
			if(!$album['title']) { $album['title'] = $old_album['title']; }
            $album['description']   = cmsCore::request('description', 'html');
            $album['description']   = $inDB->escape_string($album['description']);
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

			// если сменили категорию
			if($old_album['parent_id'] != $album['parent_id']){
				// перемещаем ее в дереве
				$inCore->nestedSetsInit('cms_photo_albums')->MoveNode($item_id, $album['parent_id']);
			}

			$inDB->update('cms_photo_albums', $album, $item_id);
			cmsCore::addSessionMessage('Альбом "'.stripslashes($album['title']).'" сохранен.', 'success');
            cmsUser::clearCsrfToken();
			cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_albums');

		}
	}

//=================================================================================================//
//=================================================================================================//

	if ($opt == 'list_albums'){

		echo '<h3>Фотоальбомы</h3>';

		//TABLE COLUMNS
		$fields = array();

		$fields[0]['title'] = 'id'; $fields[0]['field'] = 'id'; $fields[0]['width'] = '30';

		$fields[1]['title'] = 'Название'; $fields[1]['field'] = 'title'; $fields[1]['width'] = '';
		$fields[1]['link'] = '?view=components&do=config&id='.$id.'&opt=edit_album&item_id=%id%';

		$fields[2]['title'] = 'Комментарии?'; $fields[2]['field'] = 'is_comments'; $fields[2]['width'] = '95';
		$fields[2]['prc'] = 'viewAct';

		$fields[3]['title'] = 'Добавление пользователями'; $fields[3]['field'] = 'public'; $fields[3]['width'] = '100';
		$fields[3]['prc'] = 'viewAct';

		$fields[10]['title'] = 'Показ'; $fields[10]['field'] = 'published'; $fields[10]['width'] = '60';
		$fields[10]['do'] = 'opt'; $fields[10]['do_suffix'] = '_album'; //Чтобы вместо 'do=hide&id=1' было 'opt=hide_album&item_id=1'

		//ACTIONS
		$actions = array();
		$actions[0]['title'] = 'Посмотреть на сайте';
		$actions[0]['icon']  = 'search.gif';
		$actions[0]['link']  = '/photos/%id%';

		$actions[1]['title'] = 'Редактировать';
		$actions[1]['icon']  = 'edit.gif';
		$actions[1]['link']  = '?view=components&do=config&id='.$id.'&opt=edit_album&item_id=%id%';

		$actions[2]['title'] = 'Удалить';
		$actions[2]['icon']  = 'delete.gif';
		$actions[2]['confirm'] = 'Вместе с альбомом будут удалены все фотографии. Удалить фотоальбом?';
		$actions[2]['link']  = '?view=components&do=config&id='.$id.'&opt=delete_album&item_id=%id%';

		//Print table
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

//=================================================================================================//
//=================================================================================================//

	if ($opt == 'add_album' || $opt == 'edit_album'){
		if ($opt=='add_album'){
			 cpAddPathway('Фотоальбомы', '?view=components&do=config&id='.$id.'&opt=list_albums');
			 cpAddPathway('Добавить фотоальбом', '?view=components&do=config&id='.$id.'&opt=add_album');
			 echo '<h3>Добавить фотоальбом</h3>';
		} else {

            $item_id = cmsCore::request('item_id', 'int');

			$mod = $inDB->getNsCategory('cms_photo_albums', $item_id);

			 cpAddPathway('Фотоальбомы', '?view=components&do=config&id='.$id.'&opt=list_albums');
			 cpAddPathway('Редактировать фотоальбом', '?view=components&do=config&id='.$id.'&opt=add_album');
			 echo '<h3>Редактировать фотоальбом "'.$mod['title'].'"</h3>';

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
            $('img#marker_demo').attr('src', '/images/photos/small/'+file);
        }
        </script>

        <form id="addform" name="addform" method="post" action="index.php?view=components&do=config&id=<?php echo $id;?>">
        <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
        <table width="610" border="0" cellspacing="5" class="proptable">
            <tr>
                <td width="300">Название альбома:</td>
                <td><input name="title" type="text" id="title" size="30" value="<?php echo htmlspecialchars($mod['title']); ?>"/></td>
            </tr>
            <tr>
                <td valign="top">Родительский альбом:</td>
                <td valign="top">
                    <?php $rootid = $inDB->get_field('cms_photo_albums', "parent_id=0 AND NSDiffer=''", 'id'); ?>
                    <select name="parent_id" size="8" id="parent_id" style="width:285px">
                        <option value="<?php echo $rootid; ?>" <?php if (@$mod['parent_id']==$rootid || !isset($mod['parent_id'])) { echo 'selected'; }?>>-- Корневой альбом --</option>
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
                <td>Публиковать альбом?</td>
                    <td>
                        <label><input name="published" type="radio" value="1" <?php if (@$mod['published']) { echo 'checked="checked"'; } ?> /> Да</label>
                        <label><input name="published" type="radio" value="0"  <?php if (@!$mod['published']) { echo 'checked="checked"'; } ?> /> Нет</label>
                    </td>
            </tr>
            <tr>
                <td>Показывать даты и комментарии фото в списке альбома?</td>
                    <td>
                        <label><input name="showdate" type="radio" value="1" checked="checked" <?php if (@$mod['showdate']) { echo 'checked="checked"'; } ?> /> Да</label>
                        <label><input name="showdate" type="radio" value="0"  <?php if (@!$mod['showdate']) { echo 'checked="checked"'; } ?> /> Нет</label>
                    </td>
            </tr>
            <tr>
                <td valign="top">Показывать теги фото:</td>
                <td valign="top">
                    <label><input name="showtags" type="radio" value="1" checked="checked" <?php if (@$mod['showtags']) { echo 'checked="checked"'; } ?> /> Да</label>
                    <label><input name="showtags" type="radio" value="0"  <?php if (@!$mod['showtags']) { echo 'checked="checked"'; } ?> /> Нет</label>
                </td>
            </tr>
            <tr>
                <td valign="top">Показывать код для вставки на форум:</td>
                <td valign="top">
                    <label><input name="bbcode" type="radio" value="1" checked="checked" <?php if (@$mod['bbcode']) { echo 'checked="checked"'; } ?> /> Да</label>
                    <label><input name="bbcode" type="radio" value="0"  <?php if (@!$mod['bbcode']) { echo 'checked="checked"'; } ?> /> Нет</label>
                </td>
            </tr>
            <tr>
                <td valign="top">Комментарии для альбома:</td>
                <td valign="top">
                    <label><input name="is_comments" type="radio" value="1" checked="checked" <?php if (@$mod['is_comments']) { echo 'checked="checked"'; } ?> /> Да</label>
                    <label><input name="is_comments" type="radio" value="0"  <?php if (@!$mod['is_comments']) { echo 'checked="checked"'; } ?> /> Нет</label>
                </td>
            </tr>
            <tr>
                <td>Сортировать фото:</td>
                <td>
                    <select name="orderby" id="orderby" style="width:285px">
                        <option value="title" <?php if(@$mod['orderby']=='title') { echo 'selected'; } ?>>По алфавиту</option>
                        <option value="pubdate" <?php if(@$mod['orderby']=='pubdate') { echo 'selected'; } ?>>По дате</option>
                        <option value="rating" <?php if(@$mod['orderby']=='rating') { echo 'selected'; } ?>>По рейтингу</option>
                        <option value="hits" <?php if(@$mod['orderby']=='hits') { echo 'selected'; } ?>>По просмотрам</option>
                    </select>
                    <select name="orderto" id="orderto" style="width:285px">
                        <option value="desc" <?php if(@$mod['orderto']=='desc') { echo 'selected'; } ?>>по убыванию</option>
                        <option value="asc" <?php if(@$mod['orderto']=='asc') { echo 'selected'; } ?>>по возрастанию</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Вывод фотографий:</td>
                <td>
                    <select name="showtype" id="showtype" style="width:285px">
                        <option value="thumb" <?php if(@$mod['showtype']=='thumb') { echo 'selected'; } ?>>Галерея</option>
                        <option value="lightbox" <?php if(@$mod['showtype']=='lightbox') { echo 'selected'; } ?>>Галерея (лайтбокс)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Число колонок для вывода:</td>
                <td>
                    <input name="maxcols" type="text" id="maxcols" size="5" value="<?php echo @$mod['maxcols'];?>"/> шт.
                </td>
            </tr>
            <tr>
                <td>Добавление фото пользователями:</td>
                <td>
                    <select name="public" id="select" style="width:285px">
                        <option value="0" <?php if(@$mod['public']=='0') { echo 'selected'; } ?>>Запрещено</option>
                        <option value="1" <?php if(@$mod['public']=='1') { echo 'selected'; } ?>>Разрешено с премодерацией</option>
                        <option value="2" <?php if(@$mod['public']=='2') { echo 'selected'; } ?>>Разрешено без модерации</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Макс. загрузок от одного пользователя в сутки:</td>
                <td>
                    <input name="uplimit" type="text" id="uplimit" size="5" value="<?php echo @$mod['uplimit'];?>"/> шт.
                </td>
            </tr>
            <tr>
                <td>Форма сортировки:</td>
                <td>
                    <label><input name="orderform" type="radio" value="1" checked="checked" <?php if (@$mod['orderform']) { echo 'checked="checked"'; } ?> /> Показать</label>
                    <label><input name="orderform" type="radio" value="0"  <?php if (@!$mod['orderform']) { echo 'checked="checked"'; } ?> /> Скрыть</label>
                </td>
            </tr>
            <tr>
                <td>Навигация в альбоме:</td>
                <td>
                    <label><input name="nav" type="radio" value="1" <?php if (@$mod['nav']) { echo 'checked="checked"'; } ?> /> Включена</label>
                    <label><input name="nav" type="radio" value="0"  <?php if (@!$mod['nav']) { echo 'checked="checked"'; } ?> /> Выключена</label>
                </td>
            </tr>
            <tr>
                <td>CSS-префикс фотографий:</td>
                <td><input name="cssprefix" type="text" id="cssprefix" size="10" value="<?php echo @$mod['cssprefix'];?>"/></td>
            </tr>
            <tr>
                <td>Фотографий на странице:</td>
                <td>
                    <input name="perpage" type="text" id="perpage" size="5" value="<?php echo @$mod['perpage'];?>"/> шт.</td>
            </tr>
            <tr>
                <td>Ширина маленькой копии: </td>
                <td>
                    <table border="0" cellspacing="0" cellpadding="1">
                        <tr>
                            <td width="100" valign="middle">
                                <input name="thumb1" type="text" id="thumb1" size="3" value="<?php echo @$mod['thumb1'];?>"/> пикс.
                            </td>
                            <td width="100" align="center" valign="middle" style="background-color:#EBEBEB">Квадратные:</td>
                            <td width="115" align="center" valign="middle" style="background-color:#EBEBEB">
                                <input name="thumbsqr" type="radio" value="1" checked="checked" <?php if (@$mod['thumbsqr']) { echo 'checked="checked"'; } ?> /> Да
                                <input name="thumbsqr" type="radio" value="0"  <?php if (@!$mod['thumbsqr']) { echo 'checked="checked"'; } ?> />Нет
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>Ширина средней копии: </td>
                <td>
                    <input name="thumb2" type="text" id="thumb2" size="3" value="<?php echo @$mod['thumb2'];?>"/> пикс.
                </td>
            </tr>
            <?php
                if ($opt=='edit_album'){ ?>
            <tr>
                <td valign="top">Мини-эскиз:<br />
                <?php if (@$mod['iconurl']){ ?>
                <img id="marker_demo" src="/images/photos/small/<?php echo $mod['iconurl']; ?>" border="0">
                <?php  } else { ?>
                <img id="marker_demo" src="/images/photos/no_image.png" border="0">
                <?php  } ?>
                </td>
                <td valign="top">
                <?php if ($inDB->rows_count('cms_photo_files', 'album_id = '.$item_id.'')) { ?>
                        <select name="iconurl" id="iconurl" style="width:285px" onchange="showMapMarker()">
                            <?php
                                if ($mod['iconurl']){
                                    echo $inCore->getListItems('cms_photo_files', $mod['iconurl'], 'id', 'ASC', 'album_id = '.$item_id.' AND published = 1', 'file');
                                } else {
                                    echo '<option value="" selected>Выберите мини-эскиз</option>';
                                    echo $inCore->getListItems('cms_photo_files', 0, 'id', 'ASC', 'album_id = '.$item_id.' AND published = 1', 'file');
                                }
                            ?>
                        </select>
                   <?php  } else { ?>
                        В альбоме нет еще фотографий, загрузите фотографии в альбом, после выберите мини-эскиз.
                   <?php  } ?>
                </td>
            </tr>
        <?php
            }
        ?>
        </table>
        <table width="100%" border="0">
            <tr>
                <div style="margin:5px 0px 5px 0px">Описание альбома:</div>
                <textarea name="description" style="width:580px" rows="4"><?php echo @$mod['description']?></textarea>
            </tr>
        </table>

        <p>
            <input name="opt" type="hidden" id="opt" <?php if ($opt=='add_album') { echo 'value="submit_album"'; } else { echo 'value="update_album"'; } ?> />
            <input name="add_mod" type="submit" id="add_mod" <?php if ($opt=='add_album') { echo 'value="Создать альбом"'; } else { echo 'value="Сохранить альбом"'; } ?> />
            <input name="back2" type="button" id="back2" value="Отмена" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id; ?>';"/>
            <?php
                if ($opt=='edit_album'){
                    echo '<input name="item_id" type="hidden" value="'.$mod['id'].'" />';
                }
            ?>
        </p>
    </form>
		<?php
	}
        
        //=================================================================================================//
//=================================================================================================//

	if ($opt == 'add_photo' || $opt == 'edit_photo'){	
			
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
	 <?php	
	}

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
function cpPhotoAlbumById($id){
    
    $inDB = cmsDatabase::getInstance();
    $result = $inDB->query("SELECT title FROM cms_photo_albums WHERE id = $id");

	if ($inDB->num_rows($result)) {
            $cat = $inDB->fetch_assoc($result);
		return '<a href="index.php?view=components&do=config&id='.$_REQUEST['id'].'&opt=edit_album&item_id='.$id.'">'.$cat['title'].'</a> ('.$id.')';
	} else { return '--'; }

}
?>