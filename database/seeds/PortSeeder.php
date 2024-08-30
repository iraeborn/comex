<?php

use Illuminate\Database\Seeder;

class PortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ports')->insert(["name" => "Singapore"]);
        DB::table('ports')->insert(["name" => "Shenzhen, China"]);
        DB::table('ports')->insert(["name" => "Ningbo-Zhoushan, China"]);
        DB::table('ports')->insert(["name" => "Busan, South Korea"]);
        DB::table('ports')->insert(["name" => "Hong Kong, S.A.R., China"]);
        DB::table('ports')->insert(["name" => "Guangzhou Harbor, China"]);
        DB::table('ports')->insert(["name" => "Qingdao, China"]);
        DB::table('ports')->insert(["name" => "Jebel Ali, Dubai, United Arab Emirates"]);
        DB::table('ports')->insert(["name" => "Tianjin, China"]);
        DB::table('ports')->insert(["name" => "Port Klang, Malaysia"]);
        DB::table('ports')->insert(["name" => "Rotterdam, Netherlands"]);
        DB::table('ports')->insert(["name" => "Kaohsiung, Taiwan, China"]);
        DB::table('ports')->insert(["name" => "Antwerp, Belgium"]);
        DB::table('ports')->insert(["name" => "Dalian, China"]);
        DB::table('ports')->insert(["name" => "Xiamen, China"]);
        DB::table('ports')->insert(["name" => "Hamburg, Germany"]);
        DB::table('ports')->insert(["name" => "Los Angeles, U.S.A."]);
        DB::table('ports')->insert(["name" => "Tanjung Pelepas, Malaysia"]);
        DB::table('ports')->insert(["name" => "Keihin Ports, Japan"]);
        DB::table('ports')->insert(["name" => "Laem Chabang, Thailand"]);
        DB::table('ports')->insert(["name" => "Long Beach, U.S.A."]);
        DB::table('ports')->insert(["name" => "New York-New Jersey, U.S.A."]);
        DB::table('ports')->insert(["name" => "Yingkou, China"]);
        DB::table('ports')->insert(["name" => "Ho Chi Minh City, Vietnam"]);
        DB::table('ports')->insert(["name" => "Colombo, Sri Lanka"]);
        DB::table('ports')->insert(["name" => "Bremen/Bremerhaven, Germany"]);
        DB::table('ports')->insert(["name" => "Suzhou, China"]);
        DB::table('ports')->insert(["name" => "Hanshin Ports, Japan"]);
        DB::table('ports')->insert(["name" => "Tanjung Priok, Jakarta, Indonesia"]);
        DB::table('ports')->insert(["name" => "Mundra, India"]);
        DB::table('ports')->insert(["name" => "Algeciras, Spain"]);
        DB::table('ports')->insert(["name" => "Valencia, Spain"]);
        DB::table('ports')->insert(["name" => "Lianyungang, China"]);
        DB::table('ports')->insert(["name" => "Manila, Philippines"]);
        DB::table('ports')->insert(["name" => "Jawarharlal Nehru Port (Nhava Sheva), India"]);
        DB::table('ports')->insert(["name" => "Khorfakkan, U.A.E."]);
        DB::table('ports')->insert(["name" => "Felixstowe, U.K."]);
        DB::table('ports')->insert(["name" => "Haiphong, Vietnam"]);
        DB::table('ports')->insert(["name" => "Jeddah, Saudi Arabia"]);
        DB::table('ports')->insert(["name" => "Piraeus, Greece"]);
        DB::table('ports')->insert(["name" => "Savannah, U.S.A."]);
        DB::table('ports')->insert(["name" => "Seattle-Tacoma NW Seaport Alliance, U.S.A."]);
        DB::table('ports')->insert(["name" => "Santos, Brazil"]);
        DB::table('ports')->insert(["name" => "Tanjung Perak, Surabaya, Indonesia"]);
        DB::table('ports')->insert(["name" => "Salalah, Oman"]);
        DB::table('ports')->insert(["name" => "Colon, Panama"]);
        DB::table('ports')->insert(["name" => "Marsaxlokk, Malta"]);
        DB::table('ports')->insert(["name" => "Nanjing, China"]);
        DB::table('ports')->insert(["name" => "Port Said East, Egypt"]);
        DB::table('ports')->insert(["name" => "Shanghai, China"]);
        DB::table('ports')->insert(["name" => "Damietta, Egypt"]);
        DB::table('ports')->insert(["name" => "Chennai, India"]);
        DB::table('ports')->insert(["name" => "Kolkata, India"]);
        DB::table('ports')->insert(["name" => "Romney, USA"]);
        DB::table('ports')->insert(["name" => "Chicago, USA"]);
        DB::table('ports')->insert(["name" => "Norfolk, USA"]);
        DB::table('ports')->insert(["name" => "Haifa, Israel"]);
        DB::table('ports')->insert(["name" => "Mersin, Turkey"]);
        DB::table('ports')->insert(["name" => "Lattakia, Syria"]);
        DB::table('ports')->insert(["name" => "Penang, Malaysia"]);
        DB::table('ports')->insert(["name" => "Detroit, USA"]);
        DB::table('ports')->insert(["name" => "Alexandria, EGYPT"]);
        DB::table('ports')->insert(["name" => "Callao, Peru"]);
        DB::table('ports')->insert(["name" => "Bangkok, Thailand"]);
        DB::table('ports')->insert(["name" => "Samsun, Turkey"]);
        DB::table('ports')->insert(["name" => "Birganj, Nepal"]);

        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [TCP - Terminal de Contêineres de Paranaguá]",
            "code" => "9.80.13.03-3"
        ]);
        DB::table('ports')->insert([
            "name" => "Itajaí-SC [FORTE DISTRIBUIÇÃO E LOGÍSTICA DO BRASIL LTDA]",
            "code" => "9.10.30.02-1"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [COTRIGUAÇU COOPERATIVA CENTRAL]",
            "code" => "9.80.13.04-1"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [TERMINAIS PORTUARIOS DA PONTA DO FELIX S.A.]",
            "code" => "9.12.13.02-9"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [CATTALINI TERMINAIS MARITIMOS S/A]",
            "code" => "0.98.01.40-3"
        ]);
        DB::table('ports')->insert([
            "name" => "Porto Rio de Janeiro-RJ [Muti-Rio Operações Portuárias S/A]",
            "code" => "7.92.13.03-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Porto Rio de Janeiro-RJ [Libra Terminal Rio S/A]",
            "code" => "7.92.13.02-2"
        ]);
        DB::table('ports')->insert([
            "name" => "Itajaí-SC [Itazem Logistica Portuária Ltda]",
            "code" => "0.91.02.70-3"
        ]);
        DB::table('ports')->insert([
            "name" => "Porto Rio de Janeiro-RJ [Terminal de Produto Siderúrgico de São Cristóvão. INST. PORT. MAR. ALF-USO ]);PUBLICO-TRIUNFO LTDA-PT.ORG.RIO JAN/R.]",
            "code" => "7.92.13.05-7"
        ]);
        DB::table('ports')->insert([
            "name" => "Itajaí-SC [Teporti Terminal Portuário de Itajai S.A]",
            "code" => "9.10.16.03-7"
        ]);
        DB::table('ports')->insert([
            "name" => "Itajaí-SC [PORTONAVE S/A TERMINAIS PORTUÁRIOS DE NAVEGANTES]",
            "code" => "9.10.16.02-9"
        ]);
        DB::table('ports')->insert([
            "name" => "Itajaí-SC [CONEXÃO MARÍTIMA SERVIÇOS LOGÍSTICOS S/A]",
            "code" => "9.10.30.03-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Itajaí-SC [Multilog S/A]",
            "code" => "9.10.32.01-6"
        ]);
        DB::table('ports')->insert([
            "name" => "Itajaí-SC [MSC MEDITERRANEAN LOGISTICA LTDA]",
            "code" => "9.10.27.09-8"
        ]);
        DB::table('ports')->insert([
            "name" => "Porto Rio de Janeiro-RJ [Centro Logístico e Industrial Aduaneiro (CLIA) - Multiterminais Alfandegados ]);do Brasil Ltda.]",
            "code" => "7.93.32.01-3"
        ]);
        DB::table('ports')->insert([
            "name" => "Porto Rio de Janeiro-RJ [MOINHOS CRUZEIRO DO SUL S.A. ]",
            "code" => "7.92.22.11-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Salvador-BA [PORTO DE SALVADOR]",
            "code" => "5.92.13.01-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [COMPANHIA PRODUTORES DE ARMAZÉNS GERAIS]",
            "code" => "9.80.27.03-4"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [TRANSPORTE DELLA VOLPE S/A COMÉRCIO E INDUSTRIA]",
            "code" => "8.93.27.54-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [SATEL DE SANTOS TRANSPORTES EIRELI ]",
            "code" => "8.93.27.62-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Porto Rio de Janeiro-RJ [Porto Seco Juiz de Fora - Multiterminais Alfandegados do Brasil S/A ]);]",
            "code" => "6.35.32.01-8"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [PAULISTA TERMINAL RETROPORTUÁRIO LTDA]",
            "code" => "8.93.27.78-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Salvador-BA [PORTO DE ARATU - CANDEIAS]",
            "code" => "5.51.13.01-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [SANTOS BRASIL LOGISTICA SA.]",
            "code" => "8.93.32.03-2"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [SANTOS BRASIL LOGISTICA S.A.]",
            "code" => "8.93.32.04-1"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [Santos Brasil Participações AS]",
            "code" => "8.93.13.56-9"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [Bunge Alimentos S.A]",
            "code" => "8.93.22.15-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [SIGMA TRANSPORTES E LOGISTICA LTDA]",
            "code" => "8.93.27.75-8"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [REDEX  BRADO LOGISTICA S/A]",
            "code" => "0.89.32.75-8"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [Cortes Armazens Gerais ltda]",
            "code" => "8.93.27.11-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [MSC MEDITERRANEAN LOGISTICA LTDA]",
            "code" => "8.93.27.84-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [ARMAZÉNS GERAIS FASSINA LTDA]",
            "code" => "8.93.27.07-1"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [ARMAZÉNS GERAIS FASSINA LTDA]",
            "code" => "8.93.27.06-3"
        ]);
        DB::table('ports')->insert([
            "name" => "Porto Rio de Janeiro-RJ [TRANZIRAN TRANSPORTES EIRELE]",
            "code" => "7.92.27.12-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [BUNGE ALIMENTOS S.A]",
            "code" => "8.93.22.13-4"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [FOSPAR S/A]",
            "code" => "0.98.01.40-4"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [T-GRÃO CARGO TERMINAL DE GRANEIS S/A]",
            "code" => "8.93.13.24-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [Terminal Portuário Coamo]",
            "code" => "9.80.14.11-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Itajaí-SC [BRASFRIGO S/A]",
            "code" => "9.92.41.02-3"
        ]);
        DB::table('ports')->insert([
            "name" => "Porto Rio de Janeiro-RJ [TRANSPORTES MARÍTIMOS E MULTIMODAIS SÃO GERALDO LTDA]",
            "code" => "7.25.32.01-7"
        ]);
        DB::table('ports')->insert([
            "name" => "Itajaí-SC [SUPERINTENDENCIA DO PORTO DE ITAJAI]",
            "code" => "9.10.13.01-1"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [ISIS REDEX P1]",
            "code" => "0.89.32.77-3"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [ISIS REDEX PATIO 01]",
            "code" => "0.89.32.77-3"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [Cimbessul SA - Centro Integrado de Mercadorias Bens e Serviços]",
            "code" => "9.80.22.05-9"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [DEICMAR ARMAZENAGEM E DISTRIBUICAO LTDA.]",
            "code" => "8.93.27.59-4"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [DEICMAR ARMAZENAGEM E DISTRIBUICAO LTDA.]",
            "code" => "8.93.13.08-9"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [Isis Redex Pátio 2]",
            "code" => "0.89.32.78-8"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [SERRA TRANSPORTES RODOVIÁRIOS, TERMINAIS DE CONTÊINERES E LOGISTICA EIRELI ]",
            "code" => "8.93.27.85-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Itapoá-SC [CLIA - CENTRO LOGÍSTICO INTEGRADO FASTCARGO S.A.,]",
            "code" => "9.98.30.01-9"
        ]);
        DB::table('ports')->insert([
            "name" => "Porto Rio de Janeiro-RJ [TTC LOGÍSTICA LTDA ]",
            "code" => "7.92.27.13-9"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [Ecoporto Santos S.A - Pátio 03]",
            "code" => "8.93.13.45-3"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [Dínamo Inter Agricola Ltda]",
            "code" => "8.93.27.74-8"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [Ecoporto Santos S.A - Pátio 02]",
            "code" => "8.93.13.39-9"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [Termares Terminais Maritimos Especializados LTDA.]",
            "code" => "8.93.13.04-6"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [ARMAZÉNS GERAIS TERMINAL LTDA]",
            "code" => "9.80.22.03-2"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [Centro Sul Serviços Marítimos Ltda]",
            "code" => "9.80.14.10-2"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [Cereal Sul Terminal Marítimo S/A]",
            "code" => "8.93.13.51-8"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [Ecoporto Santos S.A]",
            "code" => "8.93.13.18-6"
        ]);
        DB::table('ports')->insert([
            "name" => "Itajaí-SC [APM TERMINALS ITAJAÍ S.A.]",
            "code" => "9.10.14.01-8"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [PASA - PARANÁ OPERAÇÕES PORTUÁRIAS S/A.]",
            "code" => "0.98.01.40-6"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [ULTRAFERTIL S.A.]",
            "code" => "8.93.14.02-6"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [AURORA TERMINAIS E SERVIÇOS LTDA]",
            "code" => "8.81.32.01-3"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [Interalli Administração e Participações S/A]",
            "code" => "9.80.13.06-8"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [BUNGE ALIMENTOS S/A]",
            "code" => "9.80.22.02-4"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [BUNGE ALIMENTOS S/A]",
            "code" => "9.80.14.07-2"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [S.MAGALHAES S.A. LOGISTICA EM COMERCIO EXTERIOR]",
            "code" => "8.93.27.09-8"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [Transbrasa Transitária Brasileira Ltda]",
            "code" => "8.93.13.05-4"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [LOUIS DREYFUS COMMODITIES BRASIL S/A,]",
            "code" => "9.80.14.12-9"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [Terminal Açucareiro Copersucar]",
            "code" => "8.93.14.03-4"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [SULTERMINAIS - UNIDADE JARDIM IGUACU]",
            "code" => "9.80.27.13-1"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [ESTRELA LOGISTICA E TRANSPORTES LTDA]",
            "code" => "0.89.32.72-2"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [Terminal XXXIX de Santos S.A.]",
            "code" => "8.93.13.46-1"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [RODRIMAR S/A TERMINAIS PORTUÁRIOS E ARMAZÉNS GERAIS ]",
            "code" => "8.93.13.41-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [CRAGEA - CIA REGIONAL DE ARMAZÉNS GERAIS E ENTREPOSTOS ADUANEIROS]",
            "code" => "8.94.32.07-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [S.MAGALHAES S.A. LOGISTICA EM COMERCIO EXTERIOR]",
            "code" => "8.93.27.10-1"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [HIPERCON TERMINAIS DE CARGAS LTDA]",
            "code" => "8.93.27.24-1"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [ADM DO BRASIL  LTDA , Armazéns 39,XLI e XLIII]",
            "code" => "8.93.13.21-8"
        ]);
        DB::table('ports')->insert([
            "name" => "Salvador-BA [TERMINAL PORTUÁRIO COTEGIPE S/A]",
            "code" => "5.92.14.03-1"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [COMPANHIA DOCAS DE SÃO SEBASTIÃO]",
            "code" => "8.45.13.01-2"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [EXPRESSO GUARUJÁ – TERMINAIS E SERVIÇOS LOGISTICOS LTDA ]",
            "code" => "8.93.27.92-1"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [TERMINAL 12 A S.A.]",
            "code" => "8.93.13.55-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [ROCHA TERMINAIS PORTUÁRIOS E LOGÍSTICA S.A]",
            "code" => "9.80.13.08-4"
        ]);
        DB::table('ports')->insert([
            "name" => "Salvador-BA [INTERMARITIMA PORTOS E LOGÍSTICA S.A]",
            "code" => "5.92.13.04-3"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [TERMINAL PUBLICO DE ALCOOL DE PARANAGUA - TEPAGUA]",
            "code" => "9.80.13.09-7"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [APPA - TERMINAL PUBLICO DE FERTILIZANTES - TEFER]",
            "code" => "9.80.13.07-6"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [ADMINISTRAÇÃO DOS PORTOS DE PARANAGUÁ E ANTONINA]",
            "code" => "9.80.13.01-7"
        ]);
        DB::table('ports')->insert([
            "name" => "Salvador-BA [COMPANHIA EMPÓRIO DE ARMAZÉNS GERAIS ALFANDEGADOS]",
            "code" => "5.92.32.01-3"
        ]);
        DB::table('ports')->insert([
            "name" => "Itajaí-SC [Localfrio S.A. Armazéns Gerais Frigoríficos]",
            "code" => "9.10.30.01-3"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [MARIMEX DESPACHOS TRANSPORTES E SERVIÇOS LTDA]",
            "code" => "0.89.31.34-2"
        ]);
        DB::table('ports')->insert([
            "name" => "Salvador-BA [COLUMBIA DO NORDESTE SA]",
            "code" => "5.92.32.02-1"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [EUDMARCO S/A SERVIÇOS E COMERCIO INTERNACIONAL]",
            "code" => "8.93.32.02-4"
        ]);
        DB::table('ports')->insert([
            "name" => "Itajaí-SC [Seara Alimentos LTDA. (Terminal Portuário Braskarne)]",
            "code" => "0.91.01.60-1"
        ]);
        DB::table('ports')->insert([
            "name" => "Porto Rio de Janeiro-RJ [ZIRANLOG ARMAZÉNS GERAIS E TRANSPORTES LTDA]",
            "code" => "7.92.27.12-0"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [DEICMAR ARMAZENAGEM E DISTRIBUIÇÃO LTDA]",
            "code" => "8.93.32.06-7"
        ]);
        DB::table('ports')->insert([
            "name" => "Paranaguá-PR [ROCHA TERMINAIS PORTUARIOS E LOGISTICA (ARMAZÉM 04)]",
            "code" => "9.80.13.08-4"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [NST TERMINAIS E LOGÍSTICA S/A]",
            "code" => "8.93.13.16-4"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [SUCOCITRICO CUTRALE]",
            "code" => "8.93.22.09-6"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [Companhia Bandeirantes de Armazéns Gerais]",
            "code" => "8.93.13.19-4"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [EMBRAPORT - EMPRESA BRASILEIRA DE TERMINAIS PORTUARIOS S/A]",
            "code" => "0.89.31.40-4"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [TES TERMINAL EXPORTADOR DE SANTOS SA]",
            "code" => "8.93.13.60-7"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [TEG TERMINAL EXPORTADOR DO GUARUJA LTDA]",
            "code" => "8.93.13.54-2"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [Terminal de Granéis do Guarujá S/A]",
            "code" => "8.93.13.48-8"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [LOCALFRIO S.A ARMAZÉNS GERAIS FRIGORIFICOS]",
            "code" => "8.93.30.01-3"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [MULTILOG BRASIL S/A]",
            "code" => "8.93.32.01-6"
        ]);
        DB::table('ports')->insert([
            "name" => "Itajaí-SC [BARRA DO RIO TERMINAL PORTUARIO S.A]",
            "code" => "9.10.14.02-6"
        ]);
        DB::table('ports')->insert([
            "name" => "Itajaí-SC [POLY TERMINAIS PORTUÁRIOS S/A]",
            "code" => "0.91.01.60-4"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [JBS S/A]",
            "code" => "8.93.27.61-6"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [BRASIL TERMINAL PORTUÁRIO S.A]",
            "code" => "8.93.13.59-3"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [EADI-SANTO ANDRE - TERMINAL DE CARGAS LTDA]",
            "code" => "8.94.32.08-8"
        ]);
        DB::table('ports')->insert([
            "name" => "Salvador-BA [TECON SALVADOR S/A]",
            "code" => "5.92.13.02-7"
        ]);
        DB::table('ports')->insert([
            "name" => "Santos-SP [Localfrio S/A Armazéns Gerais Frigoríficos]",
            "code" => "8.93.13.09-7"
        ]);
        DB::table('ports')->insert([
            "name" => "Itapoá-SC [Itapoá Terminais Portuários S.A]",
            "code" => "0.99.81.40-3"
        ]);
    }
}
