<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>🚨 CASidy Alarm Alert</title>
</head>
<body>
<table width="100%"  cellspacing="0" cellpadding="0">
  <tbody>
      <tr>
         <td><table width="600"  align="left" cellpadding="0" cellspacing="0">
         <!-- Main Wrapper Table with initial width set to 60opx -->
         <tbody>
            <tr>
              <!-- HTML Spacer row -->
              <td style="font-size: 0; line-height: 0;" height="20"><table width="96%" align="left"  cellpadding="0" cellspacing="0">
                  <tr>
                    <td style="font-size: 0; line-height: 0;" height="20">&nbsp;</td>
                  </tr>
                </table></td>
            </tr>
            <tr>
                <td align="left" style="font-size: 16px; font-style: normal; color: #929292; line-height: 1.6; text-align:left; padding:10px 10px; font-family: 'Helvetica Neue';">

                    <p><strong>CASidy has detected an Alarm Event.</strong></p>

                    <p>
                       {!! nl2br($alertMesage) !!}
                    </p>              
                </td>
            </tr>
        </table></td>
    </tr>
  </tbody>
</table>
</body>
</html>
