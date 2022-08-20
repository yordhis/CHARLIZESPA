<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mensaje</title>

{{-- estilos --}}

<style>
    .container{
        width: 100%;
        align-items: center;
    }

    .card{
        background-color: aliceblue;
        position: absolute;
        width: 100%;
        text-align: center;
    }

    .card-head{
        background-color: #13815c;
    }

    .card-footer{
        height: 30px;
        background-color: #13815c;
    }

    .card-text{
        padding: 2%;
        color:#000f0a;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 16px;
    }

    .text-head{
       
        color:rgb(255, 255, 255);
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 25px;
        padding: 10px 10px 10px 10px;
    }
    .link{
        color: aliceblue;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 15px;
    }

    .card-title{
        
        color:#000f0a;
            
        font-size: 20px;

    }
    .card-img-top{
        width: 75%;
        height: 25%;
    }

    a:link {
        text-decoration: none;
    }

    table, td, th {  
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        border: 1px solid #ddd;
        text-align: left;
    }

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 15px;
}
th{
    color: rgb(255, 255, 255);
    text-align: center;
    background: #13815c;
}

</style>

</head>
<body>

<div class="contariner">
 
        <div class="card" >
            <div class="card-head">
                <img class="card-img-top" src="https://charlizespa.com/images/logo_hotizontal_blanco.svg" alt="Card image">
                <h1 class="text-head">{{ $release['subject'] ?? 'Recuperación de contraseña'}}</h1>
            </div>

            <div class="card-body">

                <table>
                    <tbody>
                        <tr>
                            <th colspan="3">Nosotros</th>
                        </tr>
                        <tr>
                            <th colspan="2"> {{ $name ?? 'No hay datos' }}</th>
                        </tr>
                        <tr>
                            <th> Enlace de recuperación </th>
                            <td>
                            Para recuperar tu cuenta has clic 
                            <a href="https://charlizespa.com/password/token/{{$release['token']  ?? 'No hay datos' }}">
                                Aquí.
                            </a>    
                          </td>
                        </tr>

                    
                    
                       
                    </tbody>
                </table>

                
              
            </div>

            <div class="card-footer" style="background-color: #13815c">
               
            </div>
        </div>
   
</div>




    
  
    
</body>
</html>