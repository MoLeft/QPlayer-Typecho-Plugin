<?php
/**
 * 简洁美观非常Qの悬浮音乐播放器
 * 
 * @package QPlayer
 * @author 32mb
 * @version 1.1
 * @link https://32mb.space
 */
class QPlayer_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->header = array('QPlayer_Plugin', 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array('QPlayer_Plugin', 'footer');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
   
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){
        $jquery = new Typecho_Widget_Helper_Form_Element_Radio(
        'jquery', array('0'=> '关闭', '1'=> '开启'), 0, 'jQuery加载',
            '播放器需要jquery支持，若主题已包含jquery,则无需加载, 否则需要开启来加载插件自带的jquery。若遇到播放器无法操作也可能是播放器与主题包含的jquery不兼容，需要开启jquery加载');
        $form->addInput($jquery);

        $autoPlay = new Typecho_Widget_Helper_Form_Element_Radio(
        'autoPlay', array('0'=> '关闭', '1'=> '开启'), 0, '自动播放',
            '');
        $form->addInput($autoPlay);

        $rotate = new Typecho_Widget_Helper_Form_Element_Radio(
        'rotate', array('0'=> '关闭', '1'=> '开启'), 0, '封面旋转',
            '');
        $form->addInput($rotate);

        $color = new Typecho_Widget_Helper_Form_Element_Text('color', NULL, '', _t('自定义主色调'), _t('默认为<span style="color: #1abc9c;">#1abc9c</span>, 你可以自定义任何你喜欢的颜色作为播放器主色调。自定义主色调必须使用 Hex Color, 即`#233333`或`#333`的格式。填写错误的格式可能不会生效。'));
        $form->addInput($color);

        $musicList = new Typecho_Widget_Helper_Form_Element_Textarea('musicList', NULL, 
'{
    title: "叫做你的那个人",
    artist: "Jessica",
    cover: "https://obw915dkh.qnssl.com/cover/%E5%8F%AB%E5%81%9A%E4%BD%A0%E7%9A%84%E9%82%A3%E4%B8%AA%E4%BA%BA.jpg",
    mp3: "https://obw92zax9.qnssl.com/%E5%8F%AB%E5%81%9A%E4%BD%A0%E7%9A%84%E9%82%A3%E4%B8%AA%E4%BA%BA.mp3",
},
{
	title: "如果",
	artist: "金泰妍",
	cover: "https://obw915dkh.qnssl.com/cover/%E5%A6%82%E6%9E%9C.jpg",
	mp3: "https://obw92zax9.qnssl.com/%E5%A6%82%E6%9E%9C.mp3",
}',_t('歌曲列表'), _t('格式: {title:"xxx", artist:"xxx", cover:"http:xxxx", mp3:"http:xxxx"} ，每个歌曲之间用英文,隔开。请保证歌曲列表里至少有一首歌！'));
        $form->addInput($musicList);
    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    
    /**
     * 输出头部css
     * 
     * @access public
     * @return void
     */
    public static function header(){
        $cssUrl = Helper::options()->pluginUrl . '/QPlayer/css/player.css';
        echo '<link rel="stylesheet" href="' . $cssUrl . '">';
    }
    /**
     * 输出底部
     * 
     * @access public
     * @return void
     */
    public static function footer(){
        $options = Typecho_Widget::widget('Widget_Options')->plugin('QPlayer'); 
		echo '
			<div id="QPlayer" style="z-index:2016">
			<div id="pContent">
				<div id="player">
					<span class="cover"></span>
					<div class="ctrl">
						<div class="musicTag">
							<strong>Title</strong>
							 <span> - </span>
							<span class="artist">Artist</span>
						</div>
						<div class="progress">
							<div class="timer left">0:00</div>
							<div class="contr">
								<div class="rewind icon"></div>
								<div class="playback icon"></div>
								<div class="fastforward icon"></div>
							</div>
							<div class="right">
								<div class="liebiao icon"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="ssBtn">
				        <div class="adf"></div>
			    </div>
			</div>
			<ol id="playlist"></ol>
			</div>
             ';

        if($options->color != '') {
            echo '<style>
            #pContent .ssBtn {
                background-color:'.$options->color.';
            }
            #playlist li.playing, #playlist li:hover{
                border-left-color:'.$options->color.';
            }
            </style>';
        }
        if($options->jquery == '1'){
            echo '<script src="'. Helper::options()->pluginUrl . '/QPlayer/js/jquery.min.js' .'"></script>';
        }
        echo '
            <script>
              var autoplay = '.$options->autoPlay.';
              var playlist = [
              '.$options->musicList.'
              ];
              var isRotate = '.$options->rotate.';
            </script> ' . "\n";
		echo '<script  src="'.Helper::options()->pluginUrl . '/QPlayer/js/jquery-ui.min.js'.'"></script>' . "\n";
        echo '<script  src="'.Helper::options()->pluginUrl . '/QPlayer/js/player.js'.'"></script>' . "\n";
        
    }

}