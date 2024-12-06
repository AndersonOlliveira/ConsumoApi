  <?php
            
           $ip = "192.168.66.181"; //aqui passa o ip da máquina onde contem os ips que esta vinculado a este ip 
           
          $url = "http://". $ip ."/api/HealthMonitor/GetColetoras";
          //para usar o curl e preciso iniciar ele com o metodo abaixo pasando dentro de () a variavel
          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//CONVERTE PARA ARRAY.
          curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
          $resultado = json_decode(curl_exec($ch));
         
        foreach($resultado->Response as $Ip){
            $IpColetoras = $Ip->Ip;
            $servidor = $Ip->Nome;
            $WebService = $Ip->WebserviceCore;
            $id = $Ip->Id; 

        //$retorno = shell_exec("C:\Windows\system32\ping -n 2 $ip");
          $retorno = @fsockopen($IpColetoras,80,$errCode,$errStr,2);
           
          //busca dentra string o que for definio
          if($retorno){
               $status = "Online";

    $inserir = "INSERT INTO coletoras_onn (id_coletora, nome_coletora, Ip_coletora, status, data_onn) VALUES (:id, :servidor, :IpColetoras, :status, NOW())";

        $insert = $conexao->prepare($inserir);
        $insert->bindParam(':id', $id);
        $insert->bindParam(':servidor', $servidor);
        $insert->bindParam(':IpColetoras', $IpColetoras);
        $insert->bindParam(':status', $status);

        if ($insert->execute()) {
            //echo "Inserido com sucesso";
        } else {
            //Secho "Erro ao inserir no banco de dados";
        }


         } else{
               $status = "Offline";
               
      // inserir informações no banco 
        $inserir = "INSERT INTO coletoras_off (id_coletora, nome_coletora, Ip_coletora, status, data_off) VALUES (:id, :servidor, :IpColetoras, :status, NOW())";

        $insert = $conexao->prepare($inserir);
        $insert->bindParam(':id', $id);
        $insert->bindParam(':servidor', $servidor);
        $insert->bindParam(':IpColetoras', $IpColetoras);
        $insert->bindParam(':status', $status);

        if ($insert->execute()) {
            //echo "Inserido com sucesso";
        } else {
            //Secho "Erro ao inserir no banco de dados";
        }


}             
}             
             
?>
 