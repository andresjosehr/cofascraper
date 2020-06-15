<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Proyect;
use DB;

class TasksController extends Controller
{

    public function getLinks(Request $request)
    {
        $info = [];
        $client = new \GuzzleHttp\Client();

        $jar = \GuzzleHttp\Cookie\CookieJar::fromArray(
            [
                "__cfduid"              => "d40530c8d82b668a829fc628ee72ef08a1590203530",
                "__stripe_mid"          => "b432982f-dc58-4544-b126-db2d3900187d",
                "__stripe_sid"          => "d0201a76-ecf6-4706-a501-110bb363d484",
                "_pf_session"           => "MFNwenpGSStYWVpRSHY4ZXNLK0s2ZnZJbE9JczZGd21CKzdGVGJCS1lBKys5ekJGUjBpZVRYUkkrZDZiVDltSE1lWVU2UmJoc1c0bVJjT2pvUXdYVGFEanFYRWpQTi9VYXNTOHZlc2U3NmFWZllUVm9maENqTHdRc3d2VlFibjF1eTNscjl2UWZWbDNSMnBQNDlIMVkrSk9Cc2tMcXRmZU9zTlRpWUYwckRoamgzdzczbkFlN1BFU3dJbjVBTGJWenJncm1RNmxub1Uyc2F6SU43Q0IwT3hhcnFlSHdla3dENHN1bWhuak5uSlV4a2RuZlphTzUvM3c5SXZyTEo1dS0tTjIzQkorbTBOR3BweXVzT1RpclovZz09--836602fba1519d94effbf604700b17a1f29d9840",
                "remember_user_token"   => "W1s0NDQ4NzZdLCIkMmEkMTAkcTBCVlpTR2sxUENBeFZENkxnS3BiZSIsIjE1OTE5Njc2OTguMDE1Mjk4Il0%3D--acf3735bd6a7c75a904c3b09fb0af0aaa8aa70fe",

            ],

            ".codigofacilito.com"
        );

        $cofaPage = $client->request('GET', $request->input("curso"), [
            'cookies'        => $jar
        ]);
        
        
        $crawler = new Crawler($cofaPage->getBody()->getContents());

        $info["curso"] = $crawler->filter(".box h1")->text();

        $info["curso"];
        $info["lecciones"] = [];

        $blockLinks = [];

        $blockLinks =  $crawler->filter('.collapsible.no-border.no-box-shadow .collapsible-body.no-border.topics-li')->each(function (Crawler $node, $i) {

            $clases = $node->extract(['class'])[0];

            if(isset(explode("block-", $clases)[1])){

                $info["lecciones"][]["Titulo"] = $node->text();
                $explode = explode("block-", $clases)[1];

                $blockNumber = explode(" ", $explode)[0];

                return $blockNumber;

            }

        });


        $info["lecciones"] =  $crawler->filter('.collapsible.no-border.no-box-shadow li header')->each(function (Crawler $node, $i) {
                if(explode(".-", $node->text())){
                    return ["titulo" => explode(" arrow_drop_down", $node->text())[0]];
                }            

        });
        
        

            $i=0;
            foreach($blockLinks as $value){
                if($value){
                $cofaPage = $client->request('GET', "https://codigofacilito.com/blocks/".$value, [
                    'headers' => [
    
                        ":authority"            => "codigofacilito.com",
                        ":method"               => "GET",
                        ":path"                 => "/blocks/546",
                        ":scheme"               => "https",
                        "accept"                => "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript, */*; q=0.01",
                        "accept-language"       => "en-US,en;q=0.9",
                        "cache-control"         => "no-cache",
                        "cookie"                => "__cfduid=d1834a196e70166f886c361cbc85b77c11592064948; __stripe_mid=4a4ac703-d74b-483d-a16e-1338b00000a1; __stripe_sid=b6154848-2a84-4bd1-aca2-cc9e8a85f82e; remember_user_token=W1s0NDQ4NzZdLCIkMmEkMTAkcTBCVlpTR2sxUENBeFZENkxnS3BiZSIsIjE1OTIwNjYwNzMuMDQzMjYiXQ%3D%3D--04dd766124806dbc3b34f5fca93923eeffcecefb; _pf_session=TXczUnNXZldWbVk3L1V1bjFnZmlrYzErYTJhL2xhSW5zRFRTOFJ1Qzk0Mlc5b0VuVFkvTDNnc1FVOUZ4QUFRYzRlVnJzbkhiV05lTUFndTNQdFhJaWZGSSt1L2F6bTY3T2FiQ2kyOUpWQXFSM3J4UndKTWFndlV3TEVTeGRPNHk4SFZ2QWY3eXZSQlhjTDhVOWYzVlhhNWFPYnBDVVpDdHQ4aXNET0lRNHVVSGVNckdRV3J6SnpndnlmekpOS1pmQlJhUzB1YUpQSjcyWXltdHlkdHpINGlOL1U1bnRQckpmdkNmUXZXS2d3cWFla1dTVis5RUM1NFZ0bTk0aFZMbi0tVGVOTXVNYUM4YkhERHIwTllIK1V0UT09--9b364f5300f629f5f013d5f6b26644c5061b0f31",
                        "pragma"                => "no-cache",
                        "referer"               => "https://codigofacilito.com/cursos/php-profesional",
                        "sec-fetch-dest"        => "empty",
                        "sec-fetch-mode"        => "cors",
                        "sec-fetch-site"        => "same-origin",
                        "user-agent"            => "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36",
                        "x-csrf-token"          => "KzXyW0tN4x3EtVpBj6VFFu8UECDNfwZF/IBgcWF5JnDPTpYu3jLdXFJHK68P2/9gBcXef7uK2Fd7IY41W68YWA==",
                        "x-requested-with"      => "XMLHttpRequest"
                    ]
    
                ]);
    
    
                    header("Content-Type: text/plain");
                
                $blogContent = explode('.html("', $cofaPage->getBody()->getContents())[1];
                $blogContent = explode('");', $blogContent)[0];
    
                $blogContent= preg_replace('/\\\\/', '', $blogContent);
               
                $crawler = new Crawler($blogContent);
    
                $info["lecciones"][$i]["videos"] = [];
                $info["lecciones"][$i]["videos"] =  $crawler->filter('a')->each(function (Crawler $node, $i) {
    
                    return [
                            "link"   => "https://codigofacilito.com".$node->extract(['href'])[0],
                            "nombre" => mb_substr($node->filter(".box")->text(), 0, -1)
                           ];
    
                });
    
                $i++;
            }
        }

        if(count(explode("Examen del curso", end($info["lecciones"])["titulo"]))==2){
            array_pop($info["lecciones"]);
        }

        if(!DB::table("cursos")->where("curso", $info["curso"])->first()){
            DB::table("cursos")->insert([
                "curso" => $info["curso"]
            ]);
        }
        $i=0;
        foreach ($info["lecciones"] as $value) {
            $info["lecciones"][$i]["videos"] = self::index($value["videos"], $value["titulo"], $info["curso"], $info, $i);
            $i++;
        }

        DB::table("cursos")->where("curso", $info["curso"])->update([
            "links" => json_encode($info),
        ]);

        return "Información registrada de manera exitosa";
    }

    public function index($links, $bloqueTitulo, $cursoTitulo, $completeInfo, $lecNumber)
    {
        ini_set('memory_limit','16000000000000000000000000000000000000M');
        $i=1; $j=0;
        foreach ($links as $linkito) {

        $client = new \GuzzleHttp\Client();


        $jar = \GuzzleHttp\Cookie\CookieJar::fromArray(
            [
                "__cfduid"              => "d40530c8d82b668a829fc628ee72ef08a1590203530",
                "__stripe_mid"          => "b432982f-dc58-4544-b126-db2d3900187d",
                "__stripe_sid"          => "d0201a76-ecf6-4706-a501-110bb363d484",
                "_pf_session"           => "MFNwenpGSStYWVpRSHY4ZXNLK0s2ZnZJbE9JczZGd21CKzdGVGJCS1lBKys5ekJGUjBpZVRYUkkrZDZiVDltSE1lWVU2UmJoc1c0bVJjT2pvUXdYVGFEanFYRWpQTi9VYXNTOHZlc2U3NmFWZllUVm9maENqTHdRc3d2VlFibjF1eTNscjl2UWZWbDNSMnBQNDlIMVkrSk9Cc2tMcXRmZU9zTlRpWUYwckRoamgzdzczbkFlN1BFU3dJbjVBTGJWenJncm1RNmxub1Uyc2F6SU43Q0IwT3hhcnFlSHdla3dENHN1bWhuak5uSlV4a2RuZlphTzUvM3c5SXZyTEo1dS0tTjIzQkorbTBOR3BweXVzT1RpclovZz09--836602fba1519d94effbf604700b17a1f29d9840",
                "remember_user_token"   => "W1s0NDQ4NzZdLCIkMmEkMTAkcTBCVlpTR2sxUENBeFZENkxnS3BiZSIsIjE1OTE5Njc2OTguMDE1Mjk4Il0%3D--acf3735bd6a7c75a904c3b09fb0af0aaa8aa70fe",

            ],

            ".codigofacilito.com"
        );

        $cofaPage = $client->request('GET', $linkito["link"], [
            'cookies'        => $jar
        ]);


        $crawler = new Crawler($cofaPage->getBody()->getContents());

        $viemoURL =  $crawler->filter('#vimeo_player')->each(function (Crawler $node, $i) {

            return $node->extract(['src'])[0];

        });
        
        if(isset($viemoURL[0])){
        

        $cofaPage = $client->request('GET', $viemoURL[0],[
            "headers" => [
                "Accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
                "Accept-Encoding" => "gzip, deflate, br",
                "Accept-Language" => "es-ES,es;q=0.9",
                "Cache-Control" => "no-cache",
                "Connection" => "keep-alive",
                "Host" => "player.vimeo.com",
                "Pragma" => "no-cache",
                "Referer" => "https://codigofacilito.com/",
                "Sec-Fetch-Dest" => "iframe",
                "Sec-Fetch-Mode" => "navigate",
                "Sec-Fetch-Site" => "cross-site",
                "Upgrade-Insecure-Requests" => "1",
                "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36",
            ]
        ]);
        
        $page=$cofaPage->getBody()->getContents();

        $res = explode("config = ", $page);
        $res = explode(";", $res[1])[0];
        $res = json_decode($res, true);
        
        // header("Content-Type: text/plain");

        $qualities=["720p", "1080p", "540p","320p"];
        $videoInfo = false;

        $completeInfo["lecciones"][$lecNumber]["videos"][$j]["realLinks"] = $res["request"]["files"]["progressive"];

        foreach ($qualities as $key => $value) {
            foreach ($res["request"]["files"]["progressive"] as $value2) {
                if($value2["quality"]==$value){
                    $videoInfo = $value2;
                    break;
                }
            }
            if($videoInfo) break;
        }   
        
        if(!Storage::exists($cursoTitulo."/".$bloqueTitulo."/".$i." ".$linkito["nombre"].".mp4")){
            
            $contents = file_get_contents($videoInfo["url"]);
            Storage::put($cursoTitulo."/".$bloqueTitulo."/".$i." ".$linkito["nombre"].".mp4", $contents);
        }

        }
        $i++; $j++;
    }

    return $completeInfo["lecciones"][$lecNumber]["videos"];
        
    }

}
