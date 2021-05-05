<?php
/**
* Plugin Name: Soap Webservis Sender
* Plugin URI : https://github.com/necmettin27
* Author : Necmettin Kartal
* Author URI: https://github.com/necmettin27
* Description: Soap webservise contact form 7 verilerini göndermek.
* Version : 0.1.0
* License : 0.1.0
* License URL : https://github.com/necmettin27
* text-domain : uyumsoft.clicksuslabs.com
**/
add_action('wpcf7_mail_sent','soapsender');

function soapsender($contact_form){

	$title = $contact_form->title;
	$submission = WPCF7_Submission::get_instance();

	if($submission){
		$posted_data = $submission->get_posted_data();

		if($posted_data['ad-soyad']){
			$name = $posted_data['ad-soyad'];
		}else if($posted_data['your-name']){
			$name = $posted_data['your-name'];
		}else if($posted_data['ad']){
			$name = $posted_data['ad'];
		}

		if($posted_data['telefon']){
			$phone = $posted_data['telefon'];
		}else if($posted_data['phone']){
			$phone = $posted_data['phone'];
		}else if($posted_data['your-phone']){
			$phone = $posted_data['your-phone'];
		}

		if($posted_data['email']){
			$email = $posted_data['email'];
		}else if($posted_data['your-email']){
			$email = $posted_data['your-email'];
		}

		$a = curlsend($phone,$email,$name);
		//var_dump($a);
		//wp_die;
		return;
	}
}

function curlsend($tel,$email,$name){
	
$tarih = date('Y-m-d');
$time = date('Hi');
$time2 = date('H:i:s');
$xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
   <soapenv:Header/>
   <soapenv:Body>
      <tem:CrmFormKaydet> 
         <tem:crmformlist> 
            <tem:CrmForm> 
               <tem:geldigi_kanal>Web</tem:geldigi_kanal> 
               <tem:tip>bb</tem:tip> 
               <tem:vergi_no>11111111111</tem:vergi_no>
               <tem:sehir_ad>istanbul</tem:sehir_ad> 
               <tem:firma_email></tem:firma_email> 
               <tem:firma_web>www.uyumsoft.com</tem:firma_web> 
               <tem:firma_tel>'.$tel.'</tem:firma_tel> 
               <tem:yetkili_kisi>'.$name.'</tem:yetkili_kisi> 
               <tem:yetkili_unvan></tem:yetkili_unvan> 
               <tem:yetkili_cep_tel></tem:yetkili_cep_tel> 
               <tem:ticari_yazilim>'.$tel.'</tem:ticari_yazilim> 
               <tem:urun_hizmet>efaturaaa</tem:urun_hizmet> 
               <tem:aciklama>aaaaa</tem:aciklama>  
               <tem:firma_kod></tem:firma_kod> 
               <tem:form_ad>deneme</tem:form_ad>
               <tem:create_date>'.$tarih.'</tem:create_date> 
               <tem:create_time>'.$time.'</tem:create_time>  
               <tem:vergi_daire>esenler</tem:vergi_daire>
            </tem:CrmForm>
         </tem:crmformlist>
         <tem:izinlite> 
            <tem:Izin> 
               <tem:alici>phone</tem:alici> 
               <tem:izinTuru>EPOSTA</tem:izinTuru> 
               <tem:izinDurumu>ONAY</tem:izinDurumu> 
               <tem:izinKaynagi>HS_WEB</tem:izinKaynagi> 
               <tem:izinTarihi>'.$tarih.'T'.$time2.'</tem:izinTarihi> 
               <tem:izinFirmaTipi>BIREYSEL</tem:izinFirmaTipi> 
               <tem:izinBayiKodu></tem:izinBayiKodu>
            </tem:Izin>
         </tem:izinlite>
         <tem:kullanicikod>username</tem:kullanicikod>
         <tem:sifre>password</tem:sifre>
      </tem:CrmFormKaydet>
   </soapenv:Body>
</soapenv:Envelope>'; 
$headers = array("Content-type: text/xml;");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'urladress');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$server_output = curl_exec($ch);
curl_close($ch);

	return $server_output;
}