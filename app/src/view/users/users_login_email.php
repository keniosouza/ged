<!DOCTYPE html>
<html lang="pt-br">
<head>

    <meta charset="utf-8"/>
    <title>

        Alerta de Login | <?php echo utf8_decode(@(string)$MainGetConfigResult->name)?>

    </title>

</head>

<body style="background-color: #E5E5E5; padding: 50px;">

    <div style="width: 400px; border-radius: 3px 3px 0 0; margin-left: auto; margin-right: auto;">

        <div style="color: #ffffff; width: 100%; background-color: #182a3a; margin: 0; border-radius: 3px 3px 0 0;">

            <h1 style="margin: 0; padding: 30px 30px 30px 30px; font-family:Helvetica; font-style:normal; font-weight:normal; letter-spacing:1px; line-height:130%; text-align:left; color:#FFFFFF">

                Alerta de Login!

            </h1>

        </div>

        <div style="background-color: #ffffff; width: 100%; height: auto;">

            <h3 style="padding: 30px 30px 0 30px; margin: 0; font-family:Helvetica; font-style:normal; font-weight:normal; letter-spacing:1px; line-height:130%;">

                Olá, <?php echo utf8_decode(@(string)$UsersGetByEmailResult->name)?>

            </h3>

            <p style="padding: 30px 30px 0 30px; margin: 0; font-family:Helvetica; font-style:normal; font-weight:normal; letter-spacing:1px; line-height:130%;">

                Login realizado em sua conta: <br>

                <strong>

                    <?php echo utf8_decode(@(string)$UsersGetByEmailResult->email)?>

                </strong>

                do

                <strong>

                    <?php echo utf8_decode(@(string)$MainGetConfigResult->title)?>

                </strong>

            </p>

            <div style="padding: 30px; font-family:Helvetica; font-style:normal; font-weight:normal; letter-spacing:1px; line-height:130%;">

                <table style="width: 100%; background-color: #E5E5E5; color: #333333; border-radius: 3px">

                    <thead>

                        <tr style="text-align: left;">

                            <th scope="col" style="padding: 10px">

                                Data

                            </th>

                            <th scope="col" style="padding: 10px">

                                Horário

                            </th>

                            <th scope="col" style="padding: 10px">

                                IP

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr style="text-align: left;">

                            <td style="padding: 10px">

                                <?php echo date('d/m/Y')?>

                            </td>

                            <td style="padding: 10px">

                                <?php echo date('H:i:s')?>

                            </td>

                            <td style="padding: 10px">

                                <?php echo $_SERVER['REMOTE_ADDR']?>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</body>
</html>