<link href="assets/alert.css" rel="stylesheet">

<!-- <script>
    if(window.self == window.top){
        window.location.href="error-iframe.php"
    }
</script> -->
<?php
if(!isset($_GET['token'])){
    echo "notfound  token .....";
    exit;
}

include 'config-api.php';

$url=DOMAIN_MAIN . "/iframes/get-name-form.php?token=".$_GET['token'];
$data = file_get_contents($url);

$data=json_decode($data);

if(!$data->status){
    echo $data->msg;
    exit;
}
$fileName = $data->data->code; 
$urlGetData = DOMAIN_MAIN ."/iframes/new-index.php?token=".$_GET['token'];
$urlSaveData = DOMAIN_MAIN ."/iframes/save.php";

$ip = $_SERVER['REMOTE_ADDR'];
$dataCountry  = file_get_contents("https://freegeoip.live/json/".$ip);
$dataCountry = json_decode( $dataCountry);
$country = "EG"; //$dataCountry->country_code;

$options = $data->options;


?>
<div id="app"></div>
<script>
    var dataStyle = '<?php echo $options ?>';
    dataStyle= JSON.parse(dataStyle);
    console.log(dataStyle.price)
</script>
<script src="https://cdn.jsdelivr.net/gh/M-H-Abdelfadeil/test/<?php echo $fileName.".js" ?>"></script>
<script src="assets/alert.js"></script>
<script src="assets/jq.js"></script>

<script>
    
    $(function(){
        $.ajax({
            url: "<?php echo $urlGetData ?>",
            success: function (data) {
                data = JSON.parse(data)
               //console.log(data)
                if(data.type=="pay"){
                    $('#text').html(data.data.tokenDataPay.desc_product);
                    $('#icon').attr('src',data.data.tokenDataPay.icon_product);
                }
            }
        });


        $('form').submit(function(e){

            e.preventDefault();
            var form = $('form').serialize()+"&ip_address="+"<?php echo $ip; ?>&country=<?php echo $country; ?>&token=<?php echo $_GET['token'] ?>";
            $.ajax({
                type: "post",
                url: "<?php echo $urlSaveData ?>",
                data: form,
                success: function (data) {
                    data = JSON.parse(data)
                    console.log(data)
                    setTimeout(function(){
                            window.location.href =data.url 
                    },1000)
                    // if(!data.status){
                    //     Swal.fire({
                    //         icon:"error",
                    //         text:data.msg,
                    //     })
                    // }else{
                    //     Swal.fire({
                    //         icon:"success",
                    //         text:"تم الحجز بنجاح",
                    //     })
                    //     setTimeout(function(){
                    //         window.location.href =data.url 
                    //     },1000)
                    // }
                    
                }
            });

        })
    })
</script>