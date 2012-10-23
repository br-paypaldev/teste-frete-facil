<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>PayPal X Brasil | Teste de dependência para integração com PayPal Frete Fácil</title>

        <link rel="shortcut icon" href="http://www.paypalobjects.com/WEBSCR-640-20101108-1/en_US/i/icon/pp_favicon_x.ico" type="image/x-icon" />
        <link type="text/css" rel="stylesheet" href="https://www.paypal-brasil.com.br/x/wp-content/themes/paypal-developer/style.css?v=1307570662" />
        <!--[if lt IE 7]>
        <script type="text/javascript">
        try {
            document.execCommand("BackgroundImageCache", false, true);
        } catch(err) {}
        </script>
        <![endif]-->
        <link rel="alternate" type="application/rss+xml" title="Feed de PayPal X Brasil &raquo;" href="https://www.paypal-brasil.com.br/x/feed/" />
        <link rel="alternate" type="application/rss+xml" title="PayPal X Brasil &raquo;  Feed de comentários" href="https://www.paypal-brasil.com.br/x/comments/feed/" />
        <script type='text/javascript' src='https://www.paypal-brasil.com.br/x/wp-includes/js/jquery/jquery.js?ver=1.4.4'></script>
        <link rel='index' title='PayPal X Brasil' href='https://www.paypal-brasil.com.br/x/' />
        <style type="text/css">
        html { margin-top: 28px !important; }
            * html body { margin-top: 28px !important; }
        </style>
    </head>
    <body>
        <div id="wrapper">
            <div id="header">
                <div id="branding">
                    <a href="https://www.paypal-brasil.com.br/x" title="PayPal X Brasil" class="logo " id="top_logo">PayPal X Brasil</a>
                </div>
                <div id="social-profiles">
                     <ul>
                        <li>Siga</li>
                        <li class="social-icon twitter"><a title="Siga PayPal X Brasil" href="http://twitter.com/paypalxbrasil" target="_blank">Twitter</a></li>
                        <li class="social-icon facebook"><a title="Seja fã!" href="http://www.facebook.com/PayPalXBrasil" target="_blank">Facebook</a></li>
                        <li class="social-icon github"><a title="Contribua!" href="https://github.com/paypalxbrasil" target="_blank">GitHub</a></li>
                    </ul>
                </div>
                <div class="nav">
                    <ul iclass="menu" id="menu-navegacao-principal">
                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-71" id="menu-item-71"><a href="https://www.paypal-brasil.com.br/x/documentos-e-apis/"><span>Docs e APIs</span></a></li>
                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-59" id="menu-item-59"><a href="https://www.paypal-brasil.com.br/x/code-sample/"><span>Code Sample</span></a></li>
                        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-179" id="menu-item-179"><a href="http://pro.imasters.com.br/paypal"><span>Certificação</span></a></li>
                        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-7" id="menu-item-7"><a href="http://paypal-brasil.com.br/forum"><span>Fórum</span></a></li>
                        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-60" id="menu-item-60"><a href="http://paypal-brasil.com.br/x/xblog"><span>Use Cases</span></a></li>
                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-61" id="menu-item-61"><a href="https://www.paypal-brasil.com.br/x/tutoriais/"><span>Tutoriais</span></a></li>
                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-343" id="menu-item-343"><a href="https://www.paypal-brasil.com.br/x/extensoes/"><span>Extensões</span></a></li>
                    </ul>
                </div>
            </div>
            <div id="content" style="margin-top: 30px;">
                <h1 class="page-title">Teste de dependência para integração com PayPal Frete Fácil</h1>
                <?php
                if (class_exists('SoapClient')) {
                    echo '<h2 style="font-size: 1.2em; font-weight: bold; margin-bottom: 10px;">Dependência satisfeita</h2>';
                    echo '<p style="margin-bottom: 10px;">Você possui a extensão Soap do PHP instalada. Essa extensão é fundamental para integração com o webservice PayPal Frete Fácil.</p>';

                    $ECT = 1;
                    $FF = 2;
                    $status = 0;

                    try {
                        $location = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx';
                        $client = new SoapClient($location . '?wsdl', array('trace' => true,
                                                                            'exceptions' => true,
                                                                            'style' => SOAP_DOCUMENT,
                                                                            'use' => SOAP_LITERAL,
                                                                            'soap_version' => SOAP_1_1,
                                                                            'encoding' => 'UTF-8'));

                        $request = new stdClass();
                        $request->nCdEmpresa = '';
                        $request->sDsSenha = '';
                        $request->sCepOrigem = '01419001';
                        $request->sCepDestino = '14412300';
                        $request->nVlPeso = 1;
                        $request->nCdFormato = 1;
                        $request->nVlComprimento = 20;
                        $request->nVlAltura = 5;
                        $request->nVlLargura = 15;
                        $request->sCdMaoPropria = 'n';
                        $request->nVlValorDeclarado = 0;
                        $request->sCdAvisoRecebimento = 'n';
                        $request->nCdServico = 40010;
                        $request->nVlDiametro = 0;

                        $response = $client->CalcPrecoPrazo($request);

                        if (isset($response->CalcPrecoPrazoResult->Servicos->cServico)) {
                            $cServico = $client->CalcPrecoPrazo($request)->CalcPrecoPrazoResult->Servicos->cServico;

                            if (!isset($cServico->Erro) || $cServico->Erro == 0) {
                                if (isset($cServico->Valor) && $cServico->Valor > 0) {
                                    $status = $ECT;

                                    try {
                                        $location = 'https://ff.paypal-brasil.com.br/FretesPayPalWS/WSFretesPayPal';
                                        $client = new SoapClient($location . '?wsdl', array('trace' => true,
                                                                                            'exceptions' => true,
                                                                                            'style' => SOAP_DOCUMENT,
                                                                                            'use' => SOAP_LITERAL,
                                                                                            'soap_version' => SOAP_1_1,
                                                                                            'encoding' => 'UTF-8',
                                                                                            'location' => $location));

                                        $request = new stdClass();
                                        $request->altura = 2;
                                        $request->largura = 15;
                                        $request->peso = 1;
                                        $request->profundidade = 30;
                                        $request->cepDestino = '14412300';
                                        $request->cepOrigem = '01419001';

                                        $response = $client->getPreco($request);

                                        if (isset($response->{'return'}) && $response->{'return'} > 0) {
                                            $status |= $FF;
                                        }
                                    } catch (Exception $e) {}
                                }
                            }
                        }
                    } catch (Exception $e) {}

                    if ($status & $FF) {
                        echo '<p><b>O serviço PayPal Frete Fácil está totalmente funcional</b>.</p>';
                    } else if ($status == $ECT) {
                        echo '<p>O serviço PayPal Frete Fácil está indisponível no momento. Aguarde alguns instantes antes de tentar novamente.</p>';
                    } else {
                        echo '<p>O serviço do Correios está offline. O PayPal Frete Fácil depende desse serviço para funcionar. Aguarde alguns instantes antes de tentar novamente.</p>';
                    }
                } else {
                    echo '<h2>Extensão Soap do PHP é requerida pelo módulo Frete Fácil</h2>';
                    echo '<p>Você não possui a extensão Soap do PHP instalada. Essa extensão é fundamental para integração com o webservice PayPal Frete Fácil. Visite <a href="http://www.php.net/manual/pt_BR/soap.setup.php">Instalação e configuração</a> ou solicite a instalação da extensão ao seu administrador de hospedagem</p>';
                }
                ?>
            </div>      
        </div>
        <div id="footer">
            <div id="footer-center">
                <a class="logoRodape replace" id="footer_navRodape">PayPal | Developer Network</a>

                <div class="paypal">
                    <h4>PayPal</h4>
                    <ul>
                        <li><a href="http://www.youtube.com/watch?v=kDVLcyEMdTw">O que é PayPal</a></li>
                        <li><a href="https://www.paypal.com/br/cgi-bin/helpscr?cmd=_display-country-functionality-outside">PayPal no mundo</a></li>
                    </ul>
                </div>
                <div class="developers">
                    <h4>Developers</h4>
                    <ul>
                        <li><a target="_blank" href="http://www.paypal-brasil.com.br/forum" id="footer_HyperLink2">Fórum</a></li>
                        <li><a href="https://www.paypal-brasil.com.br/x/documentos-e-apis/" id="footer_HyperLink3">Documentos APIs</a></li>
                        <li><a href="https://www.paypal-brasil.com.br/x/code-sample/" id="footer_HyperLink5">Code Sample</a></li>
                        <li><a href="https://twitter.com/paypalxbrasil">Twitter</a></li>
                    </ul>
                </div>
                <div class="ajuda">
                    <h4>Ajuda</h4>
                    <ul>
                        <li><a href="https://www.paypal-brasil.com.br/x/fale-conosco/">Fale Conosco</a></li>
                        <li><a href="https://www.paypal-brasil.com.br/x/duvidas-tecnicas/">Duvidas Tecnicas</a></li>
                    </ul>
                </div>
                <div class="paypalDeveloper">
                    <h4>PayPal X Developer Network</h4>
                    <ul>
                        <li><a target="_blank" href="https://www.x.com/community/ppx/dev-tools">Developer Tools</a></li>
                    </ul>
                </div>
                <div id="copyright">Copyright &copy; 1999-2012 PayPal. Todos os direitos reservados.</div>
            </div>
        </div>
    </body>
</html>
