<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head runat="server">
  <style type="text/css">
    body {
      font-family: 'Open Sans', sans-serif !important;
      font-size: 10pt;
      margin: 0 1rem 0 1rem;
    }

    p {
      margin: 0;
      padding: 0;
    }

    p.parrafo {
      margin: 10px 0 10px 0;
      padding: 0;
      text-align: justify;
    }

    ol {
      margin: 10px 0 10px 0;
      padding-left: 1.7rem;
    }

    .Table {
      display: table;
      width: 100%;
      margin-top: 5px;
      margin-bottom: 5px;
    }


    .Title {
      display: table-caption;
      text-align: center;
      font-weight: 400;
      font-size: larger;
    }

    .Heading {
      font-family: 'Open Sans', sans-serif !important;
      display: table-row;
      font-weight: 500;
      text-align: center;
      color: white;
      border-color: black !important;
    }

    .Row {
      display: table-row;
      /* text-align: left; */
    }

    .Cell {
      font-family: 'Open Sans', sans-serif !important;
      display: table-cell;
      text-align: left;
      padding: 5px;
    }

    .Cell p {
      line-height: 17px;
    }

    .bordered {
      border: solid !important;
      border-color: black !important;
      border-width: thin !important;
      border-collapse: collapse;

    }

    .borderedcell {
      border-left: solid !important;
      border-right: solid !important;
      border-color: black !important;
      border-width: thin !important;
      border-collapse: collapse;
    }

    .sello .code {
      font-family: 'Libre Barcode 39 Text' !important;
      font-size: 20pt;
      line-height: 60px !important;
    }

    .bg-principal {
      background: #397BE5 !important;
    }

    .clearfix::after {
      content: "";
      clear: both;
      display: table;
    }
  </style>
</head>

<body>
  <div class="Table ">
    <div class="Row">
      <div class="Cell" style="width: 50%;">
        <div class="" style="width: 300px; text-align: center; margin-bottom: 10px;">
          <p><img src="{{ asset('/img/logo_sscom.png')}}" width="300px" /></p>
          <p><a href="http://www.sscom.cl/">www.sscom.cl</a> - Rancagua, Chile</p>
        </div>

        <div class="Table bordered cliente" style="width: 80%;">
          <div class="Heading">
            <div class="Cell bordered bg-principal">
              <p>
                Cliente</p>
            </div>
          </div>
          <div class="Row">
            <div class="Cell bordered">
              <p>Nombre:</p>
              <p>{{ $record->work->customer->name }}</p>
              <p>Giro:</p>
              <p>{{ $record->work->customer->giro }}</p>
              <p>Contacto:</p>
              <p>{{ $record->work->customer->contacto }}</p>
            </div>
          </div>
        </div>

        <div class="Table bordered atencion" style="width: 80%;">
          <div class="Heading">
            <div class="Cell bordered bg-principal">
              <p>Atencion:</p>
            </div>
          </div>
          <div class="Row">
            <div class="Cell bordered">
              <p>Nombre:</p>
              <p>{{ Auth::user()->name }}</p>
              <p>Email:</p>
              <p>{{ Auth::user()->email }}</p>
            </div>
          </div>
        </div>

      </div>


      <div class="Cell" style="width: 50%; text-align: center !important;">

        <div class="Table bordered sello"
          style="color: red !important; width: auto; margin: auto; border:5px double red !important;">
          <div class="Row">
            <div class="Cell bordered" style="text-align: center; padding: 10px !important;">
              <p style="font-size: 22pt; line-height: 20px;">COTIZACION</p>
              <p style="font-size: 14pt; line-height: normal;">{{ $record->fecha }}</p>
              <p style="font-size: 18pt; line-height: 20px;">{{ $record->codigo }}</p>
              <p class="code">{{ $record->codigo }}</p>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>

  <p class="parrafo">Por intermedio de la presente, y de acuerdo con lo conversado, me es grato hacer llegar a Usted la
    siguiente
    cotizaci&oacute;n correspondiente a <strong>&ldquo;{{ strip_tags($record->descripcion) }}&rdquo;</strong></p>
  <!-- #################### -->

  <div class="Table bordered">
    <div class="Heading">
      <div class="Cell borderedcell bg-principal" style="width: 55%;">
        <p>Detalles</p>
      </div>
      <div class="Cell borderedcell bg-principal" style="width: 10%;">
        <p>Cant</p>
      </div>
      <div class="Cell borderedcell bg-principal" style="width: 15%;">
        <p>Precio UN</p>
      </div>
      <div class="Cell borderedcell bg-principal" style="width: 20%;">
        <p>Total</p>
      </div>
    </div>
    @foreach ($record->items as $item)
    <div class="Row">
      <div class="Cell borderedcell">
        <p>{{ $item->product->nombre }}</p>
        <p class="text-xs italic ml-3">{{ strip_tags($item->descripcion) }}</p>
      </div>
      <div class="Cell borderedcell">
        <p>{{ $item->cantidad }}</p>
      </div>
      <div class="Cell borderedcell">
        <p>{{ number_format($item->precio_anotado, 0, '', '.') }}</p>
      </div>
      <div class="Cell borderedcell">
        <p>{{ number_format($item->precio_anotado * $item->cantidad, 0, '', '.') }}</p>
      </div>
    </div>
    @endforeach

  </div>

  <div class="container clearfix">
    <div class="Table bordered" style="width: 35%; float: right;">

      <div class="Row">
        <div class="Cell bordered">
          <p>Neto</p>
        </div>
        <div class="Cell bordered">
          <p>${{ number_format(($record->total_price - $record->iva_price), 0, '', '.') }}</p>
        </div>
      </div>

      <div class="Row">
        <div class="Cell bordered" style="width: 42.857%;">
          <p>IVA 19%</p>
        </div>
        <div class="Cell bordered" style="width: 57.143%;">
          <p>$ {{ number_format($record->iva_price , 0, '', '.') }}
          </p>
        </div>
      </div>

      <div class="Row">
        <div class="Cell bordered">
          <p>Total</p>
        </div>
        <div class="Cell bordered">
          <p>$ {{ number_format($record->total_price, 0, '', '.') }}</p>
        </div>
      </div>
    </div>

    <div class="Table bordered atencion" style="width: 60%; float: left;">
      <div class="Heading">
        <div class="Cell bordered bg-principal">
          <p>T&Eacute;RMINOS Y CONDICIONES</p>
        </div>
      </div>
      <div class="Row">
        <div class="Cell bordered">
          <ol>
            <li>Al cliente se le cobrar&aacute; una vez aceptada esta cotizaci&oacute;n.</li>
            <li>El pago se realizar&aacute; en un 60% antes de comenzar la ejecuci&oacute;n y el 40% restante
              posterior a la recepci&oacute;n.</li>
            <li>Este documento debe ser firmado y enviado al correo <a
                href="mailto:cotizaciones@sscom.cl">cotizaciones@sscom.cl</a></li>
            <li>En caso de encargo de conductor, este debe ser cancelado con anterioridad para ser encargado al
              proveedor.</li>
          </ol>
        </div>
      </div>
    </div>
  </div>


  <p class="parrafo">Si usted tiene alguna pregunta sobre esta cotizaci&oacute;n, por favor p&oacute;ngase en contacto
    con nosotros al
    correo <a href="mailto:contacto@sscom.cl">contacto@sscom.cl</a> o al tel&eacute;fono <a
      href="tel:+56984779110">(+56) 9 8477 9110</a></p>
  <p class="parrafo">El valor por la ejecuci&oacute;n de todo el trabajo es de acuerdo con la provisi&oacute;n de todos
    los materiales
    necesarios, transporte y log&iacute;stica, ejecuci&oacute;n, personal id&oacute;neo y cumplimiento de las normas
    vigentes en cuanto a la prevenci&oacute;n de riesgos. La validez de esta cotizaci&oacute;n es de <strong>7
      d&iacute;as</strong>.</p>
</body>

</html>
