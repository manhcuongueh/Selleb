<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TW_PLUGIN_PATH.'/PHPMailer_v2.0.4/class.phpmailer.php');

// 메일 보내기 (파일 여러개 첨부 가능)
// type : text=0, html=1, text+html=2
function mailer($fname, $fmail, $to, $subject, $content, $type=0, $file="", $cc="", $bcc="")
{
    if($type != 1)
        $content = nl2br($content);

    $mail = new PHPMailer(); // defaults to using php "mail()"
    if(defined('TW_SMTP') && TW_SMTP) {
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->Host = TW_SMTP; // SMTP server
    }
    $mail->From = $fmail;
    $mail->FromName = $fname;
    $mail->Subject = $subject;
    $mail->AltBody = ""; // optional, comment out and test
    $mail->MsgHTML($content);
    $mail->AddAddress($to);
    if($cc)
        $mail->AddCC($cc);
    if($bcc)
        $mail->AddBCC($bcc);
    //print_r2($file); exit;
    if($file != "") {
        foreach ($file as $f) {
            $mail->AddAttachment($f['path'], $f['name']);
        }
    }
    return $mail->Send();
}

// 파일을 첨부함
function attach_file($filename, $tmp_name)
{
    // 서버에 업로드 되는 파일은 확장자를 주지 않는다. (보안 취약점)
    $dest_file = TW_PATH.'/data/tmp/'.str_replace('/', '_', $tmp_name);
    move_uploaded_file($tmp_name, $dest_file);
    /*
    $fp = fopen($tmp_name, "r");
    $tmpfile = array(
        "name" => $filename,
        "tmp_name" => $tmp_name,
        "data" => fread($fp, filesize($tmp_name)));
    fclose($fp);
    */
    $tmpfile = array("name" => $filename, "path" => $dest_file);
    return $tmpfile;
}
?>