<?php
!defined('EMLOG_ROOT') && exit('error');
//设置页面加载配置文件函数
function plugin_setting_view(){
	$plugin_storage = Storage::getInstance('Ixc_safe');
	$page = $plugin_storage->getValue('page');
	?>
	<?php if (isset($_GET['succ'])): ?>
        <div class="alert alert-success">保存成功</div>
	<?php endif; ?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">域名防红</h1>
    </div>
    <ul class="nav nav-pills">
        <li class="nav-item"> <a class="nav-link active" href="#">防红设置</a></li>
        <li class="nav-item"> <a class="nav-link" target="_blank" href="store.php?action=plu&author_id=758">其他作品</a></li>
    </ul>
    <div class="card shadow mb-4 mt-2">
        <div class="card-body">
			<form method="post">
				<div class="form-group">
					<div class="form-group">
						<label>界面样式</label>
						<select name="page" class="form-control"><?php
						foreach (scandir(__DIR__ . '/page/') as $file) {
							if (strpos($file, 'style_') === 0 && strpos($file, '.php') !== false) {
								$filename = str_replace('.php', '', $file);
								$FileContent = fopen(__DIR__ . '/page/' . $file, 'rb');
								while (!feof($FileContent)) {
									$line = fgets($FileContent);
									if (strpos($line, "/*@name") !== false) {
										$start = strpos($line, "/*@name") + strlen("/*@name");
										$end = strpos($line, "*/", $start);
										$name = trim(substr($line, $start, $end - $start));
										break;
									}
								}
								fclose($FileContent);
								if (empty($name))$name = str_replace('.php', '', $filename);
								echo '<option value="'.$filename.'" ' . ($page==$filename ? 'selected' : '') .'>'.$name.'</option>';
							}
						}
						?>
						</select>
					</div>
					<br>
					<input type="submit" class="btn btn-success btn-sm" value="保存"/>
				</div>
			</form>
        </div>
    </div>
<?php }
if(!empty($_POST)){;
	$page = isset($_POST['page']) ? addslashes(trim($_POST['page'])) : '';
	$plugin_storage = Storage::getInstance('Ixc_safe');
	$plugin_storage->setValue('page', $page);
	header('Location:./plugin.php?plugin=Ixc_safe&succ=1');
}
