<?php
class Controller_Captcha extends Controller
{
	function __construct()
	{
		parent::__construct();
	}

	public function action_get($getData)
	{
		$captchaText = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$codePattern = $getData['code'];
		$captchaCode = '';

		$pattern = '/[a-zA-Z]+/';
		$parts = preg_split($pattern, $codePattern, -1, PREG_SPLIT_NO_EMPTY);

		foreach ($parts as $part) {
			$captchaCode .= $captchaText[$part];
		}
		header("Content-type: image/png");

		$im     = imagecreatetruecolor(150, 50);
		$orange = imagecolorallocate($im, 220, 210, 60);
		$black  = imagecolorallocate($im, 120, 120, 120);
		$white  = imagecolorallocate($im, 255, 255, 255);
		$px     = (imagesx($im) - 7.5 * strlen($captchaCode)) / 2;
		imagestring($im, 18, $px-10, 10, $captchaCode, $black);
		imagestring($im, 15, $px, 15, $captchaCode, $orange);
		imagestring($im, 15, $px+10, 20, $captchaCode, $white);
		imagepng($im);
		imagedestroy($im);
	}
	public function action_create($getData)
	{
		$min = 1; // Минимальное значение
		$max = 35; // Максимальное значение
		$codeText = '';
		for ($i = 0; $i < 5; $i++) {
			$captchaText = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1);
			$randomNumber = rand($min, $max);
			$codeText .= $randomNumber . $captchaText;
		}
		$data = ['code' => $codeText];

		$this->view->generate('main_view.php', 'template_api.php', $data);
	}
	public function action_check($getData)
	{

		$captchaText = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$codePattern = $getData['code'];
		$captchaCode = '';

		$pattern = '/[a-zA-Z]+/';
		$parts = preg_split($pattern, $codePattern, -1, PREG_SPLIT_NO_EMPTY);

		foreach ($parts as $part) {
			$captchaCode .= $captchaText[$part];
		}
		if ($captchaCode == strtoupper($getData['input'])){
			$data = true;
		} else {
			$data = false;
		}

		$this->view->generate('main_view.php', 'template_api.php', ['status' => $data]);
	}
}
