-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-09-2025 a las 00:53:48
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `argus`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `argus_clientes`
--

CREATE TABLE `argus_clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `nacionalidad` varchar(50) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `rfc` varchar(13) NOT NULL,
  `curp` varchar(20) NOT NULL,
  `ine` varchar(50) NOT NULL,
  `estado_civil` varchar(100) NOT NULL,
  `ocupacion` varchar(100) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `domicilio` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `beneficiario` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `argus_clientes`
--

INSERT INTO `argus_clientes` (`id`, `nombre`, `nacionalidad`, `fecha_nacimiento`, `rfc`, `curp`, `ine`, `estado_civil`, `ocupacion`, `telefono`, `domicilio`, `email`, `beneficiario`, `created_at`) VALUES
(1, 'CHUY SE LA COME', 'MEXICANA', '0000-00-00', 'LARA960419PX3', 'LALALALA', '4565465456456', 'SOLTERO', 'INGENIERO', '', 'CALLE NUMERO COLONIA', 'abe@gmail.com', 'NOMBRE DEL BENEFICIARIO (PARENTEZCO)', '2025-09-24 21:53:02'),
(2, 'CHUY SE LA COME', 'MEXICANA', '0000-00-00', 'LARA960419PX3', 'LALALALA', '4565465456456', 'SOLTERO', 'INGENIERO', '', 'CALLE NUMERO COLONIA', 'abe@gmail.com', 'NOMBRE DEL BENEFICIARIO (PARENTEZCO)', '2025-09-24 21:56:32'),
(3, 'CHUY SE LA COME', 'MEXICANA', '0000-00-00', 'LARA960419PX3', 'LALALALA', '4565465456456', 'SOLTERO', 'INGENIERO', '+525658745236', 'CALLE NUMERO COLONIA', 'abe@gmail.com', 'NOMBRE DEL BENEFICIARIO (PARENTEZCO)', '2025-09-24 21:58:47'),
(4, 'CHUY SE LA COME', 'MEXICANA', '0000-00-00', 'LARA960419PX3', 'LALALALA', '4565465456456', 'SOLTERO', 'INGENIERO', '+525658745236', 'CALLE NUMERO COLONIA', 'abe@gmail.com', 'NOMBRE DEL BENEFICIARIO (PARENTEZCO)', '2025-09-24 22:01:28'),
(5, 'CHUY SE LA COME', 'MEXICANA', '0000-00-00', 'LARA960419PX3', 'LALALALA', '4565465456456', 'SOLTERO', 'INGENIERO', '+527585485696', 'CALLE NUMERO COLONIA', 'abe@gmail.com', 'NOMBRE DEL BENEFICIARIO (PARENTEZCO)', '2025-09-24 22:14:30'),
(6, 'CHUY SE LA COME', 'MEXICANA', '0000-00-00', 'LARA960419PX3', 'LALALALA', '4565465456456', 'SOLTERO', 'INGENIERO', '', 'CALLE NUMERO COLONIA', 'abe@gmail.com', 'NOMBRE DEL BENEFICIARIO (PARENTEZCO)', '2025-09-24 22:20:29'),
(7, 'CHUY SE LA COME', 'MEXICANA', '0000-00-00', 'LARA960419PX3', 'LALALALA', '4565465456456', 'SOLTERO', 'INGENIERO', '', 'CALLE NUMERO COLONIA', 'abe@gmail.com', 'NOMBRE DEL BENEFICIARIO (PARENTEZCO)', '2025-09-24 22:28:58'),
(8, 'CHUY SE LA COME', 'MEXICANA', '0000-00-00', 'LARA960419PX3', 'LALALALA', '4565465456456', 'SOLTERO', 'INGENIERO', '', 'CALLE NUMERO COLONIA', 'abe@gmail.com', 'NOMBRE DEL BENEFICIARIO (PARENTEZCO)', '2025-09-24 22:29:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `argus_contratos_data`
--

CREATE TABLE `argus_contratos_data` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `desarrollo_id` int(11) NOT NULL,
  `datta_contrato` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`datta_contrato`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `argus_contratos_data`
--

INSERT INTO `argus_contratos_data` (`id`, `cliente_id`, `desarrollo_id`, `datta_contrato`, `created_at`) VALUES
(1, 1, 1, '{\"cliente\":{\"nombre\":\"CHUY SE LA COME\",\"nacionalidad\":\"MEXICANA\",\"fecha\":\"24 DE SEPTIEMBRE DE 2025\",\"rfc\":\"LARA960419PX3\",\"curp\":\"LALALALA\",\"ine\":\"4565465456456\",\"estado_civil\":\"SOLTERO\",\"ocupacion\":\"INGENIERO\",\"telefono\":\"\",\"domicilio\":\"CALLE NUMERO COLONIA\",\"email\":\"abe@gmail.com\",\"beneficiario\":\"NOMBRE DEL BENEFICIARIO (PARENTEZCO)\",\"edad\":0},\"desarrollo\":{\"id\":1,\"nombre\":\"VILLAS DE SAN MIGUEL\",\"tipo_contrato\":\"Type01\",\"descripcion\":\"NA\",\"superficie\":\"120\",\"clave_catastral\":\"0\",\"lotes_disponibles\":\"[\\\"HHH\\\",\\\"UUU\\\",\\\"PPP\\\"]\",\"precio_lote\":\"0.00\",\"precio_total\":\"0.00\",\"created_at\":\"2025-09-24 11:46:07\"},\"contrato\":{\"folio\":\"FOLIODELLOTE\",\"mensualidades\":1,\"superficie\":\"120\",\"superficie_fixed\":\"120 (CIENTO VEINTEM2 METROS CUADRADOS)\",\"fraccion_vendida\":\"LOTE 50 MZ 60\",\"entrega_posecion\":\"1 DE OCTUBRE DE 2025\",\"fecha_firma_contrato\":\"\",\"habitacional_colindancias\":\"ASDASDASD\",\"inicio_pagos\":\"\",\"tipo_contrato\":\"Type01\",\"monto_precio_inmueble\":\"15,000.00\",\"monto_precio_inmueble_fixed\":\"$15,000.00 (QUINCE MIL PESOS 00\\/100 M.N.)\",\"enganche\":\"50.00\",\"enganche_fixed\":\"$50.00 (CINCUENTA PESOS 00\\/100 M.N.)\",\"saldo_pago\":\"14,950.00\",\"saldo_pago_fixed\":\"$14,950.00 (CATORCE MIL NOVECIENTOS CINCUENTA PESOS 00\\/100 M.N.)\",\"parcialidades_anuales\":\"SIN PARCIALIDADES\",\"penalizacion_10\":\"3,000.00\",\"penalizacion_10_fixed\":\"$3,000.00 (TRES MIL PESOS 00\\/100 M.N.)\",\"pago_mensual\":\"50.00\",\"pago_mensual_fixed\":\"$50.00 (CINCUENTA PESOS 00\\/100 M.N.)\",\"fecha_contrato\":\"25 DE SEPTIEMBRE DE 2025\",\"fecha_contrato_fixed\":\"25 DÍAS DEL MES DE SEPTIEMBRE DEL AÑO 2025\",\"rango_pago_inicio\":\"3 DE SEPTIEMBRE DE 2025\",\"rango_pago_fin\":\"2 DE OCTUBRE DE 2025\",\"rango_pago\":\"1 MES\",\"dia_inicio\":25,\"vigencia_pagare\":\"\"}}', '2025-09-24 21:53:02'),
(2, 2, 1, '{\"cliente\":{\"nombre\":\"CHUY SE LA COME\",\"nacionalidad\":\"MEXICANA\",\"fecha\":\"24 DE SEPTIEMBRE DE 2025\",\"rfc\":\"LARA960419PX3\",\"curp\":\"LALALALA\",\"ine\":\"4565465456456\",\"estado_civil\":\"SOLTERO\",\"ocupacion\":\"INGENIERO\",\"telefono\":\"\",\"domicilio\":\"CALLE NUMERO COLONIA\",\"email\":\"abe@gmail.com\",\"beneficiario\":\"NOMBRE DEL BENEFICIARIO (PARENTEZCO)\",\"edad\":0},\"desarrollo\":{\"id\":1,\"nombre\":\"VILLAS DE SAN MIGUEL\",\"tipo_contrato\":\"Type01\",\"descripcion\":\"NA\",\"superficie\":\"120\",\"clave_catastral\":\"0\",\"lotes_disponibles\":\"[\\\"HHH\\\",\\\"UUU\\\",\\\"PPP\\\"]\",\"precio_lote\":\"0.00\",\"precio_total\":\"0.00\",\"created_at\":\"2025-09-24 11:46:07\"},\"contrato\":{\"folio\":\"FOLIODELLOTE\",\"mensualidades\":1,\"superficie\":\"120\",\"superficie_fixed\":\"120 (CIENTO VEINTEM2 METROS CUADRADOS)\",\"fraccion_vendida\":\"LOTE 50 MZ 60\",\"entrega_posecion\":\"1 DE OCTUBRE DE 2025\",\"fecha_firma_contrato\":\"\",\"habitacional_colindancias\":\"ASDASDASD\",\"inicio_pagos\":\"\",\"tipo_contrato\":\"Type01\",\"monto_precio_inmueble\":\"15,000.00\",\"monto_precio_inmueble_fixed\":\"$15,000.00 (QUINCE MIL PESOS 00\\/100 M.N.)\",\"enganche\":\"50.00\",\"enganche_fixed\":\"$50.00 (CINCUENTA PESOS 00\\/100 M.N.)\",\"saldo_pago\":\"14,950.00\",\"saldo_pago_fixed\":\"$14,950.00 (CATORCE MIL NOVECIENTOS CINCUENTA PESOS 00\\/100 M.N.)\",\"parcialidades_anuales\":\"SIN PARCIALIDADES\",\"penalizacion_10\":\"3,000.00\",\"penalizacion_10_fixed\":\"$3,000.00 (TRES MIL PESOS 00\\/100 M.N.)\",\"pago_mensual\":\"50.00\",\"pago_mensual_fixed\":\"$50.00 (CINCUENTA PESOS 00\\/100 M.N.)\",\"fecha_contrato\":\"25 DE SEPTIEMBRE DE 2025\",\"fecha_contrato_fixed\":\"25 DÍAS DEL MES DE SEPTIEMBRE DEL AÑO 2025\",\"rango_pago_inicio\":\"3 DE SEPTIEMBRE DE 2025\",\"rango_pago_fin\":\"2 DE OCTUBRE DE 2025\",\"rango_pago\":\"1 MES\",\"dia_inicio\":25,\"vigencia_pagare\":\"\"}}', '2025-09-24 21:56:32'),
(3, 3, 1, '{\"cliente\":{\"nombre\":\"CHUY SE LA COME\",\"nacionalidad\":\"MEXICANA\",\"fecha\":\"25 DE SEPTIEMBRE DE 1996\",\"rfc\":\"LARA960419PX3\",\"curp\":\"LALALALA\",\"ine\":\"4565465456456\",\"estado_civil\":\"SOLTERO\",\"ocupacion\":\"INGENIERO\",\"telefono\":\"+525658745236\",\"domicilio\":\"CALLE NUMERO COLONIA\",\"email\":\"abe@gmail.com\",\"beneficiario\":\"NOMBRE DEL BENEFICIARIO (PARENTEZCO)\",\"edad\":29},\"desarrollo\":{\"id\":1,\"nombre\":\"VILLAS DE SAN MIGUEL\",\"tipo_contrato\":\"Type01\",\"descripcion\":\"NA\",\"superficie\":\"120\",\"clave_catastral\":\"0\",\"lotes_disponibles\":\"[\\\"HHH\\\",\\\"UUU\\\",\\\"PPP\\\"]\",\"precio_lote\":\"0.00\",\"precio_total\":\"0.00\",\"created_at\":\"2025-09-24 11:46:07\"},\"contrato\":{\"folio\":\"FOLIODELLOTE\",\"mensualidades\":4,\"superficie\":\"120\",\"superficie_fixed\":\"120 (CIENTO VEINTEM2 METROS CUADRADOS)\",\"fraccion_vendida\":\"LOTE 50 MZ 60\",\"entrega_posecion\":\"24 DE SEPTIEMBRE DE 2025\",\"fecha_firma_contrato\":\"\",\"habitacional_colindancias\":\"QWEQWEQW\\r\\nQWEWQEQW\\r\\nQWEQWEQW\",\"inicio_pagos\":\"\",\"tipo_contrato\":\"Type01\",\"monto_precio_inmueble\":\"250.00\",\"monto_precio_inmueble_fixed\":\"$250.00 (DOSCIENTOS CINCUENTA PESOS 00\\/100 M.N.)\",\"enganche\":\"10.00\",\"enganche_fixed\":\"$10.00 (DIEZ PESOS 00\\/100 M.N.)\",\"saldo_pago\":\"240.00\",\"saldo_pago_fixed\":\"$240.00 (DOSCIENTOS CUARENTA PESOS 00\\/100 M.N.)\",\"parcialidades_anuales\":\"SIN PARCIALIDADES\",\"penalizacion_10\":\"50.00\",\"penalizacion_10_fixed\":\"$50.00 (CINCUENTA PESOS 00\\/100 M.N.)\",\"pago_mensual\":\"10.00\",\"pago_mensual_fixed\":\"$10.00 (DIEZ PESOS 00\\/100 M.N.)\",\"fecha_contrato\":\"24 DE SEPTIEMBRE DE 2025\",\"fecha_contrato_fixed\":\"24 DÍAS DEL MES DE SEPTIEMBRE DEL AÑO 2025\",\"rango_pago_inicio\":\"24 DE SEPTIEMBRE DE 2025\",\"rango_pago_fin\":\"13 DE FEBRERO DE 2026\",\"rango_pago\":\"4 MESES\",\"dia_inicio\":24,\"vigencia_pagare\":\"\"}}', '2025-09-24 21:58:47'),
(4, 4, 1, '{\"cliente\":{\"nombre\":\"CHUY SE LA COME\",\"nacionalidad\":\"MEXICANA\",\"fecha\":\"25 DE SEPTIEMBRE DE 1996\",\"rfc\":\"LARA960419PX3\",\"curp\":\"LALALALA\",\"ine\":\"4565465456456\",\"estado_civil\":\"SOLTERO\",\"ocupacion\":\"INGENIERO\",\"telefono\":\"+525658745236\",\"domicilio\":\"CALLE NUMERO COLONIA\",\"email\":\"abe@gmail.com\",\"beneficiario\":\"NOMBRE DEL BENEFICIARIO (PARENTEZCO)\",\"edad\":29},\"desarrollo\":{\"id\":1,\"nombre\":\"VILLAS DE SAN MIGUEL\",\"tipo_contrato\":\"Type01\",\"descripcion\":\"NA\",\"superficie\":\"120\",\"clave_catastral\":\"0\",\"lotes_disponibles\":\"[\\\"HHH\\\",\\\"UUU\\\",\\\"PPP\\\"]\",\"precio_lote\":\"0.00\",\"precio_total\":\"0.00\",\"created_at\":\"2025-09-24 11:46:07\"},\"contrato\":{\"folio\":\"FOLIODELLOTE\",\"mensualidades\":4,\"superficie\":\"120\",\"superficie_fixed\":\"120 (CIENTO VEINTEM2 METROS CUADRADOS)\",\"fraccion_vendida\":\"LOTE 50 MZ 60\",\"entrega_posecion\":\"24 DE SEPTIEMBRE DE 2025\",\"fecha_firma_contrato\":\"\",\"habitacional_colindancias\":\"QWEQWEQW\\r\\nQWEWQEQW\\r\\nQWEQWEQW\",\"inicio_pagos\":\"\",\"tipo_contrato\":\"Type01\",\"monto_precio_inmueble\":\"250.00\",\"monto_precio_inmueble_fixed\":\"$250.00 (DOSCIENTOS CINCUENTA PESOS 00\\/100 M.N.)\",\"enganche\":\"10.00\",\"enganche_fixed\":\"$10.00 (DIEZ PESOS 00\\/100 M.N.)\",\"saldo_pago\":\"240.00\",\"saldo_pago_fixed\":\"$240.00 (DOSCIENTOS CUARENTA PESOS 00\\/100 M.N.)\",\"parcialidades_anuales\":\"SIN PARCIALIDADES\",\"penalizacion_10\":\"50.00\",\"penalizacion_10_fixed\":\"$50.00 (CINCUENTA PESOS 00\\/100 M.N.)\",\"pago_mensual\":\"10.00\",\"pago_mensual_fixed\":\"$10.00 (DIEZ PESOS 00\\/100 M.N.)\",\"fecha_contrato\":\"24 DE SEPTIEMBRE DE 2025\",\"fecha_contrato_fixed\":\"24 DÍAS DEL MES DE SEPTIEMBRE DEL AÑO 2025\",\"rango_pago_inicio\":\"24 DE SEPTIEMBRE DE 2025\",\"rango_pago_fin\":\"13 DE FEBRERO DE 2026\",\"rango_pago\":\"4 MESES\",\"dia_inicio\":24,\"vigencia_pagare\":\"\"}}', '2025-09-24 22:01:28'),
(5, 5, 1, '{\"cliente\":{\"nombre\":\"CHUY SE LA COME\",\"nacionalidad\":\"MEXICANA\",\"fecha\":\"26 DE SEPTIEMBRE DE 1996\",\"rfc\":\"LARA960419PX3\",\"curp\":\"LALALALA\",\"ine\":\"4565465456456\",\"estado_civil\":\"SOLTERO\",\"ocupacion\":\"INGENIERO\",\"telefono\":\"+527585485696\",\"domicilio\":\"CALLE NUMERO COLONIA\",\"email\":\"abe@gmail.com\",\"beneficiario\":\"NOMBRE DEL BENEFICIARIO (PARENTEZCO)\",\"edad\":28},\"desarrollo\":{\"id\":1,\"nombre\":\"VILLAS DE SAN MIGUEL\",\"tipo_contrato\":\"Type01\",\"descripcion\":\"NA\",\"superficie\":\"120\",\"clave_catastral\":\"0\",\"lotes_disponibles\":\"[\\\"HHH\\\",\\\"UUU\\\",\\\"PPP\\\"]\",\"precio_lote\":\"0.00\",\"precio_total\":\"0.00\",\"created_at\":\"2025-09-24 11:46:07\"},\"contrato\":{\"folio\":\"FOLIODELLOTE\",\"mensualidades\":4,\"superficie\":\"120\",\"superficie_fixed\":\"120 (CIENTO VEINTEM2 METROS CUADRADOS)\",\"fraccion_vendida\":\"\",\"entrega_posecion\":\"26 DE SEPTIEMBRE DE 2025\",\"fecha_firma_contrato\":\"\",\"habitacional_colindancias\":\"JLKJKJLKJ\",\"inicio_pagos\":\"\",\"tipo_contrato\":\"Type01\",\"monto_precio_inmueble\":\"987.00\",\"monto_precio_inmueble_fixed\":\"$987.00 (NOVECIENTOS OCHENTA Y SIETE PESOS 00\\/100 M.N.)\",\"enganche\":\"98.00\",\"enganche_fixed\":\"$98.00 (NOVENTA Y OCHO PESOS 00\\/100 M.N.)\",\"saldo_pago\":\"889.00\",\"saldo_pago_fixed\":\"$889.00 (OCHOCIENTOS OCHENTA Y NUEVE PESOS 00\\/100 M.N.)\",\"parcialidades_anuales\":\"SIN PARCIALIDADES\",\"penalizacion_10\":\"197.40\",\"penalizacion_10_fixed\":\"$197.40 (CIENTO NOVENTA Y SIETE PESOS 40\\/100 M.N.)\",\"pago_mensual\":\"8.00\",\"pago_mensual_fixed\":\"$8.00 (OCHO PESOS 00\\/100 M.N.)\",\"fecha_contrato\":\"10 DE SEPTIEMBRE DE 2025\",\"fecha_contrato_fixed\":\"10 DÍAS DEL MES DE SEPTIEMBRE DEL AÑO 2025\",\"rango_pago_inicio\":\"27 DE SEPTIEMBRE DE 2025\",\"rango_pago_fin\":\"11 DE FEBRERO DE 2026\",\"rango_pago\":\"4 MESES\",\"dia_inicio\":10,\"vigencia_pagare\":\"\"}}', '2025-09-24 22:14:30'),
(6, 6, 1, '{\"cliente\":{\"nombre\":\"CHUY SE LA COME\",\"nacionalidad\":\"MEXICANA\",\"fecha\":\"26 DE SEPTIEMBRE DE 1996\",\"rfc\":\"LARA960419PX3\",\"curp\":\"LALALALA\",\"ine\":\"4565465456456\",\"estado_civil\":\"SOLTERO\",\"ocupacion\":\"INGENIERO\",\"telefono\":\"\",\"domicilio\":\"CALLE NUMERO COLONIA\",\"email\":\"abe@gmail.com\",\"beneficiario\":\"NOMBRE DEL BENEFICIARIO (PARENTEZCO)\",\"edad\":28},\"desarrollo\":{\"id\":1,\"nombre\":\"VILLAS DE SAN MIGUEL\",\"tipo_contrato\":\"Type01\",\"descripcion\":\"NA\",\"superficie\":\"120\",\"clave_catastral\":\"0\",\"lotes_disponibles\":\"[\\\"HHH\\\",\\\"UUU\\\",\\\"PPP\\\"]\",\"precio_lote\":\"0.00\",\"precio_total\":\"0.00\",\"created_at\":\"2025-09-24 11:46:07\"},\"contrato\":{\"folio\":\"FOLIODELLOTE\",\"mensualidades\":1,\"superficie\":\"120\",\"superficie_fixed\":\"120 (CIENTO VEINTEM2 METROS CUADRADOS)\",\"fraccion_vendida\":\"LOTE 50 MZ 60\",\"entrega_posecion\":\"25 DE SEPTIEMBRE DE 2025\",\"fecha_firma_contrato\":\"\",\"habitacional_colindancias\":\"456454\",\"inicio_pagos\":\"\",\"tipo_contrato\":\"Type01\",\"monto_precio_inmueble\":\"786,965.00\",\"monto_precio_inmueble_fixed\":\"$786,965.00 (SETE­CIENTOS OCHENTA Y SEIS MIL NOVECIENTOS SESENTA Y CINCO PESOS 00\\/100 M.N.)\",\"enganche\":\"789.00\",\"enganche_fixed\":\"$789.00 (SETECIENTOS OCHENTA Y NUEVE PESOS 00\\/100 M.N.)\",\"saldo_pago\":\"786,176.00\",\"saldo_pago_fixed\":\"$786,176.00 (SETE­CIENTOS OCHENTA Y SEIS MIL CIENTO SETENTA Y SEIS PESOS 00\\/100 M.N.)\",\"parcialidades_anuales\":\"SIN PARCIALIDADES\",\"penalizacion_10\":\"157,393.00\",\"penalizacion_10_fixed\":\"$157,393.00 (CIENTO CINCUENTA Y SIETE MIL TRESCIENTOS NOVENTA Y TRES PESOS 00\\/100 M.N.)\",\"pago_mensual\":\"7,458.00\",\"pago_mensual_fixed\":\"$7,458.00 (SIETE MIL CUATROCIENTOS CINCUENTA Y OCHO PESOS 00\\/100 M.N.)\",\"fecha_contrato\":\"25 DE SEPTIEMBRE DE 2025\",\"fecha_contrato_fixed\":\"25 DÍAS DEL MES DE SEPTIEMBRE DEL AÑO 2025\",\"rango_pago_inicio\":\"25 DE SEPTIEMBRE DE 2025\",\"rango_pago_fin\":\"25 DE SEPTIEMBRE DE 2025\",\"rango_pago\":\"1 MES\",\"dia_inicio\":25,\"vigencia_pagare\":\"\"}}', '2025-09-24 22:20:29'),
(7, 7, 1, '{\"cliente\":{\"nombre\":\"CHUY SE LA COME\",\"nacionalidad\":\"MEXICANA\",\"fecha\":\"26 DE SEPTIEMBRE DE 1996\",\"rfc\":\"LARA960419PX3\",\"curp\":\"LALALALA\",\"ine\":\"4565465456456\",\"estado_civil\":\"SOLTERO\",\"ocupacion\":\"INGENIERO\",\"telefono\":\"\",\"domicilio\":\"CALLE NUMERO COLONIA\",\"email\":\"abe@gmail.com\",\"beneficiario\":\"NOMBRE DEL BENEFICIARIO (PARENTEZCO)\",\"edad\":28},\"desarrollo\":{\"id\":1,\"nombre\":\"VILLAS DE SAN MIGUEL\",\"tipo_contrato\":\"Type01\",\"descripcion\":\"NA\",\"superficie\":\"120\",\"clave_catastral\":\"0\",\"lotes_disponibles\":\"[\\\"HHH\\\",\\\"UUU\\\",\\\"PPP\\\"]\",\"precio_lote\":\"0.00\",\"precio_total\":\"0.00\",\"created_at\":\"2025-09-24 11:46:07\"},\"contrato\":{\"folio\":\"FOLIODELLOTE\",\"mensualidades\":1,\"superficie\":\"120\",\"superficie_fixed\":\"120 (CIENTO VEINTEM2 METROS CUADRADOS)\",\"fraccion_vendida\":\"LOTE 50 MZ 60\",\"entrega_posecion\":\"25 DE SEPTIEMBRE DE 2025\",\"fecha_firma_contrato\":\"\",\"habitacional_colindancias\":\"456454\",\"inicio_pagos\":\"\",\"tipo_contrato\":\"Type01\",\"monto_precio_inmueble\":\"786,965.00\",\"monto_precio_inmueble_fixed\":\"$786,965.00 (SETE­CIENTOS OCHENTA Y SEIS MIL NOVECIENTOS SESENTA Y CINCO PESOS 00\\/100 M.N.)\",\"enganche\":\"789.00\",\"enganche_fixed\":\"$789.00 (SETECIENTOS OCHENTA Y NUEVE PESOS 00\\/100 M.N.)\",\"saldo_pago\":\"786,176.00\",\"saldo_pago_fixed\":\"$786,176.00 (SETE­CIENTOS OCHENTA Y SEIS MIL CIENTO SETENTA Y SEIS PESOS 00\\/100 M.N.)\",\"parcialidades_anuales\":\"SIN PARCIALIDADES\",\"penalizacion_10\":\"157,393.00\",\"penalizacion_10_fixed\":\"$157,393.00 (CIENTO CINCUENTA Y SIETE MIL TRESCIENTOS NOVENTA Y TRES PESOS 00\\/100 M.N.)\",\"pago_mensual\":\"7,458.00\",\"pago_mensual_fixed\":\"$7,458.00 (SIETE MIL CUATROCIENTOS CINCUENTA Y OCHO PESOS 00\\/100 M.N.)\",\"fecha_contrato\":\"25 DE SEPTIEMBRE DE 2025\",\"fecha_contrato_fixed\":\"25 DÍAS DEL MES DE SEPTIEMBRE DEL AÑO 2025\",\"rango_pago_inicio\":\"25 DE SEPTIEMBRE DE 2025\",\"rango_pago_fin\":\"25 DE SEPTIEMBRE DE 2025\",\"rango_pago\":\"1 MES\",\"dia_inicio\":25,\"vigencia_pagare\":\"\"}}', '2025-09-24 22:28:58'),
(8, 8, 1, '{\"cliente\":{\"nombre\":\"CHUY SE LA COME\",\"nacionalidad\":\"MEXICANA\",\"fecha\":\"26 DE SEPTIEMBRE DE 1996\",\"rfc\":\"LARA960419PX3\",\"curp\":\"LALALALA\",\"ine\":\"4565465456456\",\"estado_civil\":\"SOLTERO\",\"ocupacion\":\"INGENIERO\",\"telefono\":\"\",\"domicilio\":\"CALLE NUMERO COLONIA\",\"email\":\"abe@gmail.com\",\"beneficiario\":\"NOMBRE DEL BENEFICIARIO (PARENTEZCO)\",\"edad\":0},\"desarrollo\":{\"id\":1,\"nombre\":\"VILLAS DE SAN MIGUEL\",\"tipo_contrato\":\"Type01\",\"descripcion\":\"NA\",\"superficie\":\"120\",\"clave_catastral\":\"0\",\"lotes_disponibles\":\"[\\\"HHH\\\",\\\"UUU\\\",\\\"PPP\\\"]\",\"precio_lote\":\"0.00\",\"precio_total\":\"0.00\",\"created_at\":\"2025-09-24 11:46:07\"},\"contrato\":{\"folio\":\"FOLIODELLOTE\",\"mensualidades\":4,\"superficie\":\"120\",\"superficie_fixed\":\"120 (CIENTO VEINTEM2 METROS CUADRADOS)\",\"fraccion_vendida\":\"LOTE 50 MZ 60\",\"entrega_posecion\":\"26 DE SEPTIEMBRE DE 2025\",\"fecha_firma_contrato\":\"\",\"habitacional_colindancias\":\"JLKJKJLKJ\",\"inicio_pagos\":\"\",\"tipo_contrato\":\"Type01\",\"monto_precio_inmueble\":\"987.00\",\"monto_precio_inmueble_fixed\":\"\",\"enganche\":\"98.00\",\"enganche_fixed\":\"\",\"saldo_pago\":\"0.00\",\"saldo_pago_fixed\":\"\",\"parcialidades_anuales\":\"SIN PARCIALIDADES\",\"penalizacion_10\":\"0.00\",\"penalizacion_10_fixed\":\"\",\"pago_mensual\":\"8.00\",\"pago_mensual_fixed\":\"\",\"fecha_contrato\":\"10 DE SEPTIEMBRE DE 2025\",\"fecha_contrato_fixed\":\"\",\"rango_pago_inicio\":\"27 DE SEPTIEMBRE DE 2025\",\"rango_pago_fin\":\"11 DE FEBRERO DE 2026\",\"rango_pago\":\"4 MESES\",\"dia_inicio\":10,\"vigencia_pagare\":\"\"}}', '2025-09-24 22:29:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `argus_desarrollos`
--

CREATE TABLE `argus_desarrollos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo_contrato` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `superficie` varchar(100) NOT NULL,
  `clave_catastral` varchar(100) NOT NULL,
  `lotes_disponibles` text NOT NULL,
  `precio_lote` decimal(15,2) NOT NULL,
  `precio_total` decimal(15,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `argus_desarrollos`
--

INSERT INTO `argus_desarrollos` (`id`, `nombre`, `tipo_contrato`, `descripcion`, `superficie`, `clave_catastral`, `lotes_disponibles`, `precio_lote`, `precio_total`, `created_at`) VALUES
(1, 'VILLAS DE SAN MIGUEL', 'Type01', 'NA', '120', '0', '[\"HHH\",\"UUU\",\"PPP\"]', 0.00, 0.00, '2025-09-24 17:46:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `argus_plantillas`
--

CREATE TABLE `argus_plantillas` (
  `id` int(11) NOT NULL,
  `tipo_contrato_id` int(11) DEFAULT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `argus_plantillas`
--

INSERT INTO `argus_plantillas` (`id`, `tipo_contrato_id`, `nombre_archivo`, `ruta_archivo`, `created_at`) VALUES
(1, 1, 'VILLAS DE SAN MIGUEL TEST FINAL (3).docx', 'vistas/plantillas/tpl_68d42d5448d12.docx', '2025-09-24 17:41:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `argus_users`
--

CREATE TABLE `argus_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `permission` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `argus_users`
--

INSERT INTO `argus_users` (`id`, `username`, `password`, `permission`, `created_at`) VALUES
(1, 'admin', '$2y$10$yJTe2YD/AmWGxNWNwmr25OZIJI0rcV5rUkjrECBfw4gFoJz2rYWPS', 'admin', '2025-09-24 17:09:18'),
(2, 'abelomas', '$2y$10$yJTe2YD/AmWGxNWNwmr25OZIJI0rcV5rUkjrECBfw4gFoJz2rYWPS', 'user', '2025-09-24 17:10:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `argus_variables`
--

CREATE TABLE `argus_variables` (
  `id` int(11) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `identificador` varchar(150) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `argus_variables`
--

INSERT INTO `argus_variables` (`id`, `tipo`, `identificador`, `nombre`, `created_at`) VALUES
(1, 'tipo_contrato', 'Type01', 'VILLAS DE SAN MIGUEL', '2025-09-24 17:41:07'),
(2, 'nacionalidad', 'MEX', 'MEXICANA', '2025-09-24 17:46:44');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `argus_clientes`
--
ALTER TABLE `argus_clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `argus_contratos_data`
--
ALTER TABLE `argus_contratos_data`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `argus_desarrollos`
--
ALTER TABLE `argus_desarrollos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `argus_plantillas`
--
ALTER TABLE `argus_plantillas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tipo` (`tipo_contrato_id`);

--
-- Indices de la tabla `argus_users`
--
ALTER TABLE `argus_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indices de la tabla `argus_variables`
--
ALTER TABLE `argus_variables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identificador` (`identificador`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `argus_clientes`
--
ALTER TABLE `argus_clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `argus_contratos_data`
--
ALTER TABLE `argus_contratos_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `argus_desarrollos`
--
ALTER TABLE `argus_desarrollos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `argus_plantillas`
--
ALTER TABLE `argus_plantillas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `argus_users`
--
ALTER TABLE `argus_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `argus_variables`
--
ALTER TABLE `argus_variables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `argus_plantillas`
--
ALTER TABLE `argus_plantillas`
  ADD CONSTRAINT `fk_tipo` FOREIGN KEY (`tipo_contrato_id`) REFERENCES `argus_variables` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
