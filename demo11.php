<?php
	
	//https://bing.sunweihu.com//201502/201502%20%2829%29
	
	
	
	
	
	for($i=0;$i<35;$i++){
		$url = 'https://bing.sunweihu.com/2015%E5%B9%B4%E5%BF%85%E5%BA%94%E5%A3%81%E7%BA%B8/201511/201511 ('.$i.').jpg';
		$response = getHTML($url);
		if($response['code'] == 200){
			$imgDir = './2015/11/';   //必须是项目所在的绝对路径
			//要生成的图片名字
			$filename = '201511'.$i.'.png'; //新图片名称
			$newFilePath = $imgDir.$filename;
			$data = $response['data'];
			$newFile = fopen($newFilePath,"w"); //打开文件准备写入
			fwrite($newFile,$data); //写入二进制流到文件
			fclose($newFile); //关闭文件
			
			sleep(2);
		}else{
			
			continue;
		}
	}
	
	
	
	function get_image($issue){
		set_time_limit(0);
		$url = 'http://www.economist.com/printedition/'.$issue;
		$header = [
				'Host:www.economist.com',
				'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:67.0) Gecko/20100101 Firefox/67.0',
				'Accept-Language: zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
				'Accept-Encoding: gzip, deflate, br',
				'Connection: keep-alive',
				'Cookie: economist_has_visited_app_before=true; geo_country=GB; geo_region=UK; rvjourney=NewEconomistSite/50/50/NewEconomistSite; rvuuid=64c7883626606d1aad76060e62091e91; visid_incap_121505=fvb7DYTzSHukPgJ60z8b9rj9010AAAAAQUIPAAAAAAB7+Y6I/THg2vBlvGcLSt19; nlbi_121505=OdS4JjDyXxVYlquvntKrYQAAAABEN+4+5cuHLajelKSvNV2N; incap_ses_197_121505=ZSTyJSP5lDzySMD9QeW7Arn9010AAAAAB4Ps5mxsAKtMO3D7yZLQtg==; utag_main=v_id:016e84171e4d00444001c86a0af00104d001a00d009dc$_sn:1$_se:3$_ss:0$_st:1574176296246$ses_id:1574174137936%3Bexp-session$_pn:4%3Bexp-session; ext_pgvwcount=4.9; hibext_instdsigdipv2=1; _gcl_au=1.1.1234797693.1574174239; btIdentify=e822afc6-7553-4a9e-aa62-6b9ecda84b64; _bti=%7B%22app_id%22%3A%22economist-prod%22%2C%22bsin%22%3A%22qAdR1IruAi2LkeBVYK3PkAXFj096eqI9jaxkO4TIQGhtcqIy3KmqR82eB0lB6B0yAjXOixYmyAKriMpit%2F5GnQ%3D%3D%22%2C%22created_at%22%3A%222019-11-19T14%3A37%3A26%2B00%3A00%22%2C%22last_updated%22%3A%222019-11-19T14%3A37%3A26%2B00%3A00%22%2C%22user_id%22%3A%2204ae7d06-e70a-4fbe-a820-6d2a26c3da64%3A1574174241.85%22%7D; _bts=5b894cfb-3f21-4df3-e668-6463e882d7ae; incap_ses_198_121505=P08JQGx2OndXHaLU3nK/Ahf/010AAAAAg56VVA5bUleln8YQFlh3aw==; _evidon_consent_cookie={"consent_date":"2019-11-19T14:41:37.597Z"}',
				'Upgrade-Insecure-Requests: 1',
				'Cache-Control: max-age=0',
			];
			
			
		$html = $this->getHTML($url,$header);
		$reg = '/<div class="main-content__main-column print-edition__content">\s*<div class="print-edition__cover-wrapper">\s*<div class="print-edition__cover-widget"><a href=".*?">\s*<div class="component-image print-edition__cover-widget__image">\s*<img src="(.*?)".*?\/>.*?<\/div>\s*?<ul class="list">\s*(.*?)\s*<\/ul>\s*<a .*?>.*?<\/a>.*?<\/div>/si';
		preg_match($reg,$html,$match_result);
		
	
		if(!empty($match_result)){
			//$url = str_replace('https://','http://',$match_result[1]);
			$url = $match_result[1];
			$date_info = explode('-',$issue);
			$year = $date_info[0];
			$file_name = implode($date_info,'');
			$suffix = pathinfo($url, PATHINFO_EXTENSION);
			$file_name .= '.'.$suffix;
			
			$content = $this->getHTML($url,$header,false);
			$save_path = WEB_PATH.'/uploads/economist/'.$year.'/';
			
			if(!is_dir($save_path)){
				mkdir($save_path,755,true);
			}
			
			file_put_contents($save_path.$file_name,$content);
			$result = ['code'=>200,'msg'=>'图片采集成功','cover'=>'/economist/'.$year.'/'.$file_name];
		}else{
			$result = ['code'=>100,'msg'=>'图片采集失败'];
		}
		return $result;
	}
	
	
	function getHTML($url,$header=[]){
	   set_time_limit(0);
	   if(empty($header)){
		   $parse_url = parse_url($url);
		   $header = [
				'Host:'.$parse_url['host'],
				'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:67.0) Gecko/20100101 Firefox/67.0',
			];
	   }
		
		//p($header);
		
		//初使化curl资源
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		
		curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
		
		curl_setopt($curl, CURLOPT_ENCODING, "gzip");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	//数据存到成字符串吧，别给我直接输出到屏幕了
		curl_setopt($curl, CURLOPT_TIMEOUT,300);          //单位 秒，也可以使用
		curl_setopt($curl, CURLOPT_HEADER,0);
		curl_setopt($curl, CURLOPT_NOBODY,0);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);  
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1); 
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		 // 在尝试连接时等待的秒数
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,300);
		//curl_setopt($curl, CURLOPT_SSLVERSION, 3);


		//执行并获取HTML文档内容
		$data = curl_exec($curl);
		//curl是否出错
		if (curl_errno($curl)) {
			//获取curl错误信息
			$msg = curl_error($curl); 
			
			$result = ['code'=>100,'msg'=>'采集失败，错误原因：'.$msg];
		}else{
			$result = ['code'=>200,'msg'=>'采集成功','data'=>$data];
		}
		curl_close($curl); //用完记得关掉他
		return $result;
	}