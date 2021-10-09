-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-02-2020 a las 00:07:41
-- Versión del servidor: 10.3.16-MariaDB
-- Versión de PHP: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `salon`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banco`
--

CREATE TABLE `banco` (
  `IDBANCO` int(11) NOT NULL,
  `DESCRIPCION` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `banco`
--

INSERT INTO `banco` (`IDBANCO`, `DESCRIPCION`) VALUES
(1, 'Banrural'),
(2, 'G&T'),
(3, 'Agromercantil');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `idBitacora` int(11) NOT NULL,
  `fechaHora` date NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `tabla` varchar(45) NOT NULL,
  `actividad` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `ingreso` double NOT NULL,
  `retiro` double NOT NULL,
  `saldo` decimal(8,2) DEFAULT NULL,
  `motivo` text COLLATE ucs2_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci;

--
-- Volcado de datos para la tabla `caja`
--

INSERT INTO `caja` (`id`, `fecha`, `ingreso`, `retiro`, `saldo`, `motivo`) VALUES
(2890, '2020-02-14', 100, 0, '100.00', ''),
(2891, '2020-02-14', 100, 0, '200.00', ''),
(2892, '2020-02-14', 50, 0, '250.00', ''),
(2893, '2020-02-14', 100, 0, '350.00', ''),
(2894, '2020-02-14', 50, 0, '400.00', ''),
(2895, '2020-02-14', 50, 0, '450.00', ''),
(2896, '2020-02-14', 50, 0, '500.00', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja_estado`
--

CREATE TABLE `caja_estado` (
  `ID` int(11) NOT NULL,
  `ESTADO` varchar(25) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `caja_estado`
--

INSERT INTO `caja_estado` (`ID`, `ESTADO`) VALUES
(1, 'ABIERTA'),
(1, 'ABIERTA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carga`
--

CREATE TABLE `carga` (
  `idcarga` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `idproducto` varchar(20) COLLATE ucs2_spanish2_ci NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_unitario` decimal(9,2) NOT NULL,
  `precio_total` decimal(9,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogoegresos`
--

CREATE TABLE `catalogoegresos` (
  `idCatalogoEgresos` int(11) NOT NULL,
  `descripcion` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `catalogoegresos`
--

INSERT INTO `catalogoegresos` (`idCatalogoEgresos`, `descripcion`) VALUES
(1, 'Teléfono'),
(2, 'Canon de Agua'),
(3, 'Energía Eléctrica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `idCategoria` int(11) NOT NULL,
  `descripcion` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`idCategoria`, `descripcion`) VALUES
(1, 'Cosméticos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  `dpi` bigint(13) DEFAULT NULL,
  `nit` varchar(15) COLLATE ucs2_spanish2_ci NOT NULL,
  `direccion` varchar(125) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  `departamento` varchar(100) COLLATE ucs2_spanish2_ci NOT NULL,
  `municipio` varchar(100) COLLATE ucs2_spanish2_ci NOT NULL,
  `referencia` varchar(150) COLLATE ucs2_spanish2_ci NOT NULL,
  `telefono` varchar(45) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  `email` varchar(45) COLLATE ucs2_spanish2_ci NOT NULL,
  `idTipo_cliente` int(11) NOT NULL,
  `saldo` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idcliente`, `nombre`, `dpi`, `nit`, `direccion`, `departamento`, `municipio`, `referencia`, `telefono`, `email`, `idTipo_cliente`, `saldo`) VALUES
(1, 'C/F', 0, '0', 'CIUDAD', 'CIUDAD', 'CIUDAD', 'CIUDAD', '00', '', 1, '0.00'),
(2, 'Test Cliente', 0, '123456', '', '', '', '', '', '', 1, '500.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `idCompra` int(11) NOT NULL,
  `idProveedor` int(11) NOT NULL,
  `noFactura` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `idTipoCompra` int(11) DEFAULT NULL,
  `idFormaPago` int(11) DEFAULT NULL,
  `noCheque` bigint(15) NOT NULL,
  `nocuenta` bigint(20) DEFAULT NULL,
  `total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentabancaria`
--

CREATE TABLE `cuentabancaria` (
  `nocuenta` bigint(20) NOT NULL,
  `PROPIETARIO` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `TIPOCUENTA` int(11) NOT NULL,
  `BANCO` int(11) NOT NULL,
  `SALDO` decimal(9,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deposito`
--

CREATE TABLE `deposito` (
  `iddeposito` bigint(20) NOT NULL,
  `fecha` date NOT NULL,
  `nocuenta` bigint(20) NOT NULL,
  `motivo` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `total` decimal(9,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descuento`
--

CREATE TABLE `descuento` (
  `idDescuento` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `porcentaje` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `idDetalleCompra` int(11) NOT NULL,
  `idCompra` int(11) NOT NULL,
  `idproducto` varchar(20) CHARACTER SET ucs2 COLLATE ucs2_spanish2_ci NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precioUnitario` double(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_devolucion`
--

CREATE TABLE `detalle_devolucion` (
  `id_detalle_devolucion` int(11) NOT NULL,
  `id_devolucion` int(11) NOT NULL,
  `idproducto` varchar(20) COLLATE ucs2_spanish2_ci NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `subtotal` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id_detalle_venta` int(11) NOT NULL,
  `idventa` int(11) NOT NULL,
  `idproducto` varchar(100) COLLATE ucs2_spanish2_ci NOT NULL,
  `cantidad` float NOT NULL,
  `idDescuento` int(11) DEFAULT NULL,
  `subtotal` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devolucion`
--

CREATE TABLE `devolucion` (
  `id_devolucion` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `idcliente` int(11) NOT NULL,
  `idventa` int(11) NOT NULL,
  `total` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formapago`
--

CREATE TABLE `formapago` (
  `idFormapago` int(11) NOT NULL,
  `descripcion` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `formapago`
--

INSERT INTO `formapago` (`idFormapago`, `descripcion`) VALUES
(1, 'Efectivo'),
(2, 'Cheque'),
(3, 'Depósito');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gasto`
--

CREATE TABLE `gasto` (
  `noDocumento` int(11) NOT NULL,
  `tipoDocumento` int(11) NOT NULL,
  `idEgreso` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `idFormaPago` int(11) NOT NULL,
  `noCheque` bigint(15) NOT NULL,
  `banco` int(11) DEFAULT NULL,
  `noCuenta` bigint(15) DEFAULT NULL,
  `motivo` text NOT NULL,
  `total` double(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `idInventario` int(9) NOT NULL,
  `idproducto` varchar(20) CHARACTER SET ucs2 COLLATE ucs2_spanish2_ci NOT NULL,
  `fecha` date NOT NULL,
  `ingreso` int(9) NOT NULL,
  `egreso` int(9) NOT NULL,
  `tipoMovimiento` text NOT NULL,
  `existencia` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login`
--

CREATE TABLE `login` (
  `user` varchar(25) COLLATE ucs2_spanish2_ci NOT NULL,
  `pass` varchar(150) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  `nombre` varchar(100) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  `rol` int(11) DEFAULT NULL,
  `token` varchar(50) COLLATE ucs2_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci;

--
-- Volcado de datos para la tabla `login`
--

INSERT INTO `login` (`user`, `pass`, `nombre`, `rol`, `token`) VALUES
('admin', '202cb962ac59075b964b07152d234b70', 'Administrador', 0, '0'),
('caja', '202cb962ac59075b964b07152d234b70', 'Caja', 1, '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `IDMARCA` int(10) NOT NULL,
  `NOMBRE` varchar(50) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_cliente`
--

CREATE TABLE `pago_cliente` (
  `idpago_cliente` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `idcliente` int(11) NOT NULL,
  `idFormaPago` int(11) NOT NULL,
  `noCheque` bigint(15) NOT NULL,
  `banco` varchar(100) COLLATE ucs2_spanish2_ci NOT NULL,
  `nocuenta` bigint(20) NOT NULL,
  `total_abono` decimal(8,2) DEFAULT NULL,
  `saldoAnterior` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci;

--
-- Volcado de datos para la tabla `pago_cliente`
--

INSERT INTO `pago_cliente` (`idpago_cliente`, `fecha`, `idcliente`, `idFormaPago`, `noCheque`, `banco`, `nocuenta`, `total_abono`, `saldoAnterior`) VALUES
(1, '2020-02-14', 2, 1, 0, '', 0, '100.00', 0),
(2, '2020-02-14', 2, 1, 0, '', 0, '100.00', 0),
(3, '2020-02-14', 2, 1, 0, '', 0, '50.00', 800),
(4, '2020-02-14', 2, 1, 0, '', 0, '100.00', 750),
(5, '2020-02-14', 2, 1, 0, '', 0, '50.00', 650),
(6, '2020-02-14', 2, 1, 0, '', 0, '50.00', 600),
(7, '2020-02-14', 2, 1, 0, '', 0, '50.00', 550);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_proveedor`
--

CREATE TABLE `pago_proveedor` (
  `idpago_proveedor` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `idProveedor` int(11) NOT NULL,
  `idFormaPago` int(11) NOT NULL,
  `noCheque` bigint(15) NOT NULL,
  `banco` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `nocuenta` bigint(20) NOT NULL,
  `total_abono` decimal(8,2) NOT NULL,
  `saldoAnterior` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `pago_proveedor`
--

INSERT INTO `pago_proveedor` (`idpago_proveedor`, `fecha`, `idProveedor`, `idFormaPago`, `noCheque`, `banco`, `nocuenta`, `total_abono`, `saldoAnterior`) VALUES
(1, '2020-02-13', 2, 1, 0, '', 0, '100.00', 0),
(2, '0000-00-00', 1, 1, 0, '', 0, '150.00', 0),
(3, '0000-00-00', 1, 1, 0, '', 0, '100.00', 0),
(4, '2020-02-13', 1, 1, 0, '', 0, '150.00', 0),
(5, '0000-00-00', 1, 1, 0, '', 0, '100.00', 0),
(6, '2019-09-16', 1, 1, 0, '', 0, '50.00', 0),
(7, '0000-00-00', 1, 1, 0, '', 0, '50.00', 0),
(8, '2020-02-13', 1, 1, 0, '', 0, '50.00', 0),
(9, '2019-08-19', 1, 1, 0, '', 0, '50.00', 0),
(10, '2020-02-13', 1, 1, 0, '', 0, '50.00', 0),
(11, '2020-02-13', 1, 1, 0, '', 0, '50.00', 0),
(12, '2020-02-14', 1, 1, 0, '', 0, '50.00', 0),
(13, '2020-02-14', 1, 1, 0, '', 0, '50.00', 0),
(14, '2020-02-14', 1, 2, 1, 'Banrural', 0, '100.00', 0),
(15, '2020-02-14', 1, 1, 0, '', 0, '100.00', 0),
(16, '2020-02-14', 1, 1, 0, '', 0, '200.00', 3900),
(17, '2020-02-14', 1, 1, 0, '', 0, '50.00', 3700),
(18, '2020-02-14', 1, 1, 0, '', 0, '250.00', 3650);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `idproducto` varchar(100) COLLATE ucs2_spanish2_ci NOT NULL,
  `idCategoria` int(11) DEFAULT NULL,
  `nombre` varchar(100) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  `preciocosto` decimal(8,2) DEFAULT NULL,
  `precioSugerido` decimal(8,2) DEFAULT NULL,
  `precioTop` decimal(8,2) DEFAULT NULL,
  `marca` int(50) DEFAULT NULL,
  `serie` varchar(50) COLLATE ucs2_spanish2_ci NOT NULL,
  `modelo` varchar(50) COLLATE ucs2_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `idProveedor` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `nombreComercial` varchar(120) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `departamento` varchar(150) NOT NULL,
  `municipio` varchar(120) NOT NULL,
  `telefono` int(8) NOT NULL,
  `http` varchar(85) NOT NULL,
  `email` varchar(45) NOT NULL,
  `contacto` varchar(120) NOT NULL,
  `telefonoContacto` int(8) NOT NULL,
  `saldo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`idProveedor`, `nombre`, `nombreComercial`, `direccion`, `departamento`, `municipio`, `telefono`, `http`, `email`, `contacto`, `telefonoContacto`, `saldo`) VALUES
(1, 'Pruebas del sistema', 'Dany Otoniel ', 'Santa Rita', 'El Progreso', 'Guastatoya', 55552222, '', '', '', 0, 3400),
(2, 'Test Proveedor', 'Test Proveedor', 'Guastatoya', '', '', 0, '', '', '', 0, 9900);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocliente`
--

CREATE TABLE `tipocliente` (
  `idTipoCliente` int(11) NOT NULL,
  `descripcion` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipocliente`
--

INSERT INTO `tipocliente` (`idTipoCliente`, `descripcion`) VALUES
(1, 'Mayorista');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocombustible`
--

CREATE TABLE `tipocombustible` (
  `idTipoCombustible` int(11) NOT NULL,
  `descripcion` varchar(120) NOT NULL,
  `idp` float(3,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocompra`
--

CREATE TABLE `tipocompra` (
  `idTipoCompra` int(11) NOT NULL,
  `descripcion` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipocompra`
--

INSERT INTO `tipocompra` (`idTipoCompra`, `descripcion`) VALUES
(1, 'Credito'),
(2, 'Contado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocuenta`
--

CREATE TABLE `tipocuenta` (
  `IDTIPOCUENTA` int(11) NOT NULL,
  `DESCRIPCION` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `tipocuenta`
--

INSERT INTO `tipocuenta` (`IDTIPOCUENTA`, `DESCRIPCION`) VALUES
(1, 'Monetaria'),
(2, 'Ahorro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodocumento`
--

CREATE TABLE `tipodocumento` (
  `idTipoDo` int(11) NOT NULL,
  `descripcion` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipodocumento`
--

INSERT INTO `tipodocumento` (`idTipoDo`, `descripcion`) VALUES
(1, 'Factura'),
(2, 'Recibo'),
(3, 'Vale'),
(4, 'Nota de Envío');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipogasto`
--

CREATE TABLE `tipogasto` (
  `idTipoGasto` int(11) NOT NULL,
  `descripcion` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_venta`
--

CREATE TABLE `tipo_venta` (
  `idtipo_venta` int(11) NOT NULL,
  `nombre` varchar(45) COLLATE ucs2_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci;

--
-- Volcado de datos para la tabla `tipo_venta`
--

INSERT INTO `tipo_venta` (`idtipo_venta`, `nombre`) VALUES
(1, 'Contado'),
(2, 'Crédito'),
(3, 'Proforma ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `idventa` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `idcliente` int(11) NOT NULL,
  `idtipo_venta` int(11) NOT NULL,
  `idFormapago` int(11) NOT NULL,
  `noCheque` bigint(15) DEFAULT NULL,
  `banco` int(11) DEFAULT NULL,
  `nocuenta` bigint(20) DEFAULT NULL,
  `total` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `banco`
--
ALTER TABLE `banco`
  ADD PRIMARY KEY (`IDBANCO`);

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`idBitacora`);

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `carga`
--
ALTER TABLE `carga`
  ADD PRIMARY KEY (`idcarga`),
  ADD KEY `fk_carga_producto_idx` (`idproducto`);

--
-- Indices de la tabla `catalogoegresos`
--
ALTER TABLE `catalogoegresos`
  ADD PRIMARY KEY (`idCatalogoEgresos`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`idCategoria`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idcliente`),
  ADD KEY `fk_cliente_tipo_cliente` (`idTipo_cliente`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`idCompra`),
  ADD KEY `01_proveedor_id` (`idProveedor`),
  ADD KEY `02_tipocompra_id` (`idTipoCompra`),
  ADD KEY `03_formapago_id` (`idFormaPago`),
  ADD KEY `04_cuenta_no` (`nocuenta`);

--
-- Indices de la tabla `cuentabancaria`
--
ALTER TABLE `cuentabancaria`
  ADD PRIMARY KEY (`nocuenta`),
  ADD KEY `01_fk_tipocuenta_id` (`TIPOCUENTA`),
  ADD KEY `02_fk_banco_idbanco` (`BANCO`);

--
-- Indices de la tabla `deposito`
--
ALTER TABLE `deposito`
  ADD PRIMARY KEY (`iddeposito`),
  ADD KEY `01_fk_cuenta_nocuenta` (`nocuenta`);

--
-- Indices de la tabla `descuento`
--
ALTER TABLE `descuento`
  ADD PRIMARY KEY (`idDescuento`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`idDetalleCompra`),
  ADD KEY `fk_detalle_compra_compra` (`idCompra`),
  ADD KEY `fk_detalle_compra_producto` (`idproducto`);

--
-- Indices de la tabla `detalle_devolucion`
--
ALTER TABLE `detalle_devolucion`
  ADD PRIMARY KEY (`id_detalle_devolucion`),
  ADD KEY `fk_detalle_devolucion_devolucion1_idx` (`id_devolucion`),
  ADD KEY `fk_detalle_devolucion_producto1_idx` (`idproducto`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id_detalle_venta`),
  ADD KEY `fk_detalle_venta_venta1_idx` (`idventa`),
  ADD KEY `fk_detalle_venta_producto1_idx` (`idproducto`),
  ADD KEY `fk_detalle_venta_descuento` (`idDescuento`);

--
-- Indices de la tabla `devolucion`
--
ALTER TABLE `devolucion`
  ADD PRIMARY KEY (`id_devolucion`),
  ADD KEY `01_fk_cliente_codigo` (`idcliente`),
  ADD KEY `02_fk_venta_id` (`idventa`);

--
-- Indices de la tabla `formapago`
--
ALTER TABLE `formapago`
  ADD PRIMARY KEY (`idFormapago`);

--
-- Indices de la tabla `gasto`
--
ALTER TABLE `gasto`
  ADD PRIMARY KEY (`noDocumento`),
  ADD KEY `fk_01_gasto_egreso` (`idEgreso`),
  ADD KEY `fk_02_gasto_tipo_documento` (`tipoDocumento`),
  ADD KEY `fk_03_gasto_forma_pago` (`idFormaPago`),
  ADD KEY `05_fk_cuenta_No` (`noCuenta`),
  ADD KEY `04_fk_banco_id` (`banco`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`idInventario`),
  ADD KEY `fk_inventario_idproducto` (`idproducto`);

--
-- Indices de la tabla `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`user`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`IDMARCA`);

--
-- Indices de la tabla `pago_cliente`
--
ALTER TABLE `pago_cliente`
  ADD PRIMARY KEY (`idpago_cliente`),
  ADD KEY `fk_02_formaPago` (`idFormaPago`),
  ADD KEY `fk_01_idCliente` (`idcliente`);

--
-- Indices de la tabla `pago_proveedor`
--
ALTER TABLE `pago_proveedor`
  ADD PRIMARY KEY (`idpago_proveedor`),
  ADD KEY `1_proveedor_id` (`idProveedor`),
  ADD KEY `2_formapago_id` (`idFormaPago`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`idproducto`),
  ADD KEY `01_fk_categoria_idcate` (`idCategoria`),
  ADD KEY `02_fk_marca_id` (`marca`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`idProveedor`);

--
-- Indices de la tabla `tipocliente`
--
ALTER TABLE `tipocliente`
  ADD PRIMARY KEY (`idTipoCliente`);

--
-- Indices de la tabla `tipocombustible`
--
ALTER TABLE `tipocombustible`
  ADD PRIMARY KEY (`idTipoCombustible`);

--
-- Indices de la tabla `tipocompra`
--
ALTER TABLE `tipocompra`
  ADD PRIMARY KEY (`idTipoCompra`);

--
-- Indices de la tabla `tipocuenta`
--
ALTER TABLE `tipocuenta`
  ADD PRIMARY KEY (`IDTIPOCUENTA`);

--
-- Indices de la tabla `tipodocumento`
--
ALTER TABLE `tipodocumento`
  ADD PRIMARY KEY (`idTipoDo`);

--
-- Indices de la tabla `tipogasto`
--
ALTER TABLE `tipogasto`
  ADD PRIMARY KEY (`idTipoGasto`);

--
-- Indices de la tabla `tipo_venta`
--
ALTER TABLE `tipo_venta`
  ADD PRIMARY KEY (`idtipo_venta`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`idventa`),
  ADD KEY `01_fk_cliente` (`idcliente`) USING BTREE,
  ADD KEY `02_fk_venta` (`idtipo_venta`) USING BTREE,
  ADD KEY `03_fk_formapago` (`idFormapago`),
  ADD KEY `fk_05` (`nocuenta`),
  ADD KEY `fk_04` (`banco`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `banco`
--
ALTER TABLE `banco`
  MODIFY `IDBANCO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `idBitacora` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2897;

--
-- AUTO_INCREMENT de la tabla `carga`
--
ALTER TABLE `carga`
  MODIFY `idcarga` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `catalogoegresos`
--
ALTER TABLE `catalogoegresos`
  MODIFY `idCatalogoEgresos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `idCategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `idCompra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `descuento`
--
ALTER TABLE `descuento`
  MODIFY `idDescuento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `idDetalleCompra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_devolucion`
--
ALTER TABLE `detalle_devolucion`
  MODIFY `id_detalle_devolucion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id_detalle_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `devolucion`
--
ALTER TABLE `devolucion`
  MODIFY `id_devolucion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `formapago`
--
ALTER TABLE `formapago`
  MODIFY `idFormapago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `idInventario` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `IDMARCA` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago_cliente`
--
ALTER TABLE `pago_cliente`
  MODIFY `idpago_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pago_proveedor`
--
ALTER TABLE `pago_proveedor`
  MODIFY `idpago_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `idProveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipocliente`
--
ALTER TABLE `tipocliente`
  MODIFY `idTipoCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipocombustible`
--
ALTER TABLE `tipocombustible`
  MODIFY `idTipoCombustible` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipocompra`
--
ALTER TABLE `tipocompra`
  MODIFY `idTipoCompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipocuenta`
--
ALTER TABLE `tipocuenta`
  MODIFY `IDTIPOCUENTA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipodocumento`
--
ALTER TABLE `tipodocumento`
  MODIFY `idTipoDo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipogasto`
--
ALTER TABLE `tipogasto`
  MODIFY `idTipoGasto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipo_venta`
--
ALTER TABLE `tipo_venta`
  MODIFY `idtipo_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `idventa` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carga`
--
ALTER TABLE `carga`
  ADD CONSTRAINT `fk_carga_producto` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`idproducto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `fk_cliente_tipo_cliente` FOREIGN KEY (`idTipo_cliente`) REFERENCES `tipocliente` (`idTipoCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `01_proveedor_id` FOREIGN KEY (`idProveedor`) REFERENCES `proveedor` (`idProveedor`),
  ADD CONSTRAINT `02_tipocompra_id` FOREIGN KEY (`idTipoCompra`) REFERENCES `tipocompra` (`idTipoCompra`),
  ADD CONSTRAINT `03_formapago_id` FOREIGN KEY (`idFormaPago`) REFERENCES `formapago` (`idFormapago`),
  ADD CONSTRAINT `04_cuenta_no` FOREIGN KEY (`nocuenta`) REFERENCES `cuentabancaria` (`nocuenta`);

--
-- Filtros para la tabla `cuentabancaria`
--
ALTER TABLE `cuentabancaria`
  ADD CONSTRAINT `01_fk_tipocuenta_id` FOREIGN KEY (`TIPOCUENTA`) REFERENCES `tipocuenta` (`IDTIPOCUENTA`),
  ADD CONSTRAINT `02_fk_banco_idbanco` FOREIGN KEY (`BANCO`) REFERENCES `banco` (`IDBANCO`);

--
-- Filtros para la tabla `deposito`
--
ALTER TABLE `deposito`
  ADD CONSTRAINT `01_fk_cuenta_nocuenta` FOREIGN KEY (`nocuenta`) REFERENCES `cuentabancaria` (`nocuenta`);

--
-- Filtros para la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `fk_detalle_compra_compra` FOREIGN KEY (`idCompra`) REFERENCES `compra` (`idCompra`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_detalle_compra_producto` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`idproducto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_devolucion`
--
ALTER TABLE `detalle_devolucion`
  ADD CONSTRAINT `fk_detalle_devolucion_devolucion1` FOREIGN KEY (`id_devolucion`) REFERENCES `devolucion` (`id_devolucion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_detalle_devolucion_producto1` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`idproducto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `fk_detalle_venta_descuento` FOREIGN KEY (`idDescuento`) REFERENCES `descuento` (`idDescuento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_detalle_venta_producto1` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`idproducto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_detalle_venta_venta1` FOREIGN KEY (`idventa`) REFERENCES `venta` (`idventa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `devolucion`
--
ALTER TABLE `devolucion`
  ADD CONSTRAINT `01_fk_cliente_codigo` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`idcliente`),
  ADD CONSTRAINT `02_fk_venta_id` FOREIGN KEY (`idventa`) REFERENCES `venta` (`idventa`);

--
-- Filtros para la tabla `gasto`
--
ALTER TABLE `gasto`
  ADD CONSTRAINT `01_fk_gasto_tipo_documento` FOREIGN KEY (`tipoDocumento`) REFERENCES `tipodocumento` (`idTipoDo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `02_fk_gasto_egreso` FOREIGN KEY (`idEgreso`) REFERENCES `catalogoegresos` (`idCatalogoEgresos`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `03_fk_gasto_forma_pago` FOREIGN KEY (`idFormaPago`) REFERENCES `formapago` (`idFormapago`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `04_fk_banco_id` FOREIGN KEY (`banco`) REFERENCES `banco` (`IDBANCO`),
  ADD CONSTRAINT `05_fk_cuenta_No` FOREIGN KEY (`noCuenta`) REFERENCES `cuentabancaria` (`nocuenta`);

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `fk_inventario_idproducto` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`idproducto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pago_cliente`
--
ALTER TABLE `pago_cliente`
  ADD CONSTRAINT `fk_01_idCliente` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`idcliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_02_formaPago` FOREIGN KEY (`idFormaPago`) REFERENCES `formapago` (`idFormapago`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pago_proveedor`
--
ALTER TABLE `pago_proveedor`
  ADD CONSTRAINT `1_proveedor_id` FOREIGN KEY (`idProveedor`) REFERENCES `proveedor` (`idProveedor`),
  ADD CONSTRAINT `2_formapago_id` FOREIGN KEY (`idFormaPago`) REFERENCES `formapago` (`idFormapago`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `01_fk_categoria_idcate` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`),
  ADD CONSTRAINT `02_fk_marca_id` FOREIGN KEY (`marca`) REFERENCES `marca` (`IDMARCA`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `fk_01` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`idcliente`),
  ADD CONSTRAINT `fk_02` FOREIGN KEY (`idtipo_venta`) REFERENCES `tipo_venta` (`idtipo_venta`),
  ADD CONSTRAINT `fk_03` FOREIGN KEY (`idFormapago`) REFERENCES `formapago` (`idFormapago`),
  ADD CONSTRAINT `fk_04` FOREIGN KEY (`banco`) REFERENCES `banco` (`IDBANCO`),
  ADD CONSTRAINT `fk_05` FOREIGN KEY (`nocuenta`) REFERENCES `cuentabancaria` (`nocuenta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
