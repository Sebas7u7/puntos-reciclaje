-- Crear base de datos
CREATE DATABASE IF NOT EXISTS ecogestordb;
USE ecogestordb;

-- Configuración inicial
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET NAMES utf8mb4 */;

-- Tabla cuenta
CREATE TABLE cuenta (
  idCuenta INT(11) NOT NULL AUTO_INCREMENT,
  correo VARCHAR(45) NOT NULL,
  clave VARCHAR(45) NOT NULL,
  rol INT(11) NOT NULL,
  estado INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (idCuenta),
  UNIQUE KEY correo_UNIQUE (correo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla colaborador
CREATE TABLE colaborador (
  idColaborador INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(45) NOT NULL,
  servicio_ofrecido VARCHAR(100) NOT NULL,
  telefono VARCHAR(20) DEFAULT NULL,
  direccion VARCHAR(200) DEFAULT NULL,
  foto_perfil VARCHAR(255) DEFAULT NULL,
  idCuenta INT(11) NOT NULL,
  PRIMARY KEY (idColaborador),
  KEY fk_Colaborador_Cuenta1_idx (idCuenta),
  CONSTRAINT colaborador_ibfk_1 FOREIGN KEY (idCuenta) REFERENCES cuenta (idCuenta)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla residuo
CREATE TABLE residuo (
  idResiduo INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  descripcion LONGTEXT DEFAULT NULL,
  categoria VARCHAR(100) NOT NULL,
  PRIMARY KEY (idResiduo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla colaborador_has_residuo
CREATE TABLE colaborador_has_residuo (
  Colaborador_idColaborador INT(11) NOT NULL,
  Residuo_idResiduo INT(11) NOT NULL,
  observaciones TEXT NULL,
  PRIMARY KEY (Colaborador_idColaborador, Residuo_idResiduo),
  KEY Residuo_idResiduo (Residuo_idResiduo),
  CONSTRAINT colaborador_has_residuo_ibfk_1 FOREIGN KEY (Colaborador_idColaborador) REFERENCES colaborador (idColaborador),
  CONSTRAINT colaborador_has_residuo_ibfk_2 FOREIGN KEY (Residuo_idResiduo) REFERENCES residuo (idResiduo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla usuario
CREATE TABLE usuario (
  idUsuario INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(45) NOT NULL,
  apellido VARCHAR(45) NOT NULL,
  telefono VARCHAR(20) DEFAULT NULL,
  nickname VARCHAR(45) DEFAULT NULL,
  foto_perfil VARCHAR(255) DEFAULT NULL,
  idCuenta INT(11) NOT NULL,
  PRIMARY KEY (idUsuario),
  KEY fk_Usuario_Cuenta1_idx (idCuenta),
  CONSTRAINT usuario_ibfk_1 FOREIGN KEY (idCuenta) REFERENCES cuenta (idCuenta)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla publicacion
CREATE TABLE publicacion (
  idPublicacion INT(11) NOT NULL AUTO_INCREMENT,
  titulo MEDIUMTEXT NOT NULL,
  descripcion LONGTEXT DEFAULT NULL,
  tipo VARCHAR(200) NOT NULL,
  fecha_publicacion DATE NOT NULL,
  enlace LONGTEXT DEFAULT NULL,
  Colaborador_idColaborador INT(11) DEFAULT NULL,
  PRIMARY KEY (idPublicacion),
  KEY fk_Publicacion_Colaborador1_idx (Colaborador_idColaborador),
  CONSTRAINT publicacion_ibfk_1 FOREIGN KEY (Colaborador_idColaborador) REFERENCES colaborador (idColaborador)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla punto_recoleccion
CREATE TABLE punto_recoleccion (
  idPunto_Recoleccion INT(11) NOT NULL AUTO_INCREMENT,
  nombre LONGTEXT NOT NULL,
  direccion VARCHAR(200) NOT NULL,
  latitud DECIMAL(10,8) NOT NULL,
  longitud DECIMAL(11,8) NOT NULL,
  estado VARCHAR(45) NOT NULL,
  Colaborador_idColaborador INT(11) NOT NULL,
  PRIMARY KEY (idPunto_Recoleccion),
  KEY fk_Punto_Recoleccion_Colaborador1_idx (Colaborador_idColaborador),
  CONSTRAINT punto_recoleccion_ibfk_1 FOREIGN KEY (Colaborador_idColaborador) REFERENCES colaborador (idColaborador)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla punto_residuo
CREATE TABLE punto_residuo (
  Residuo_idResiduo INT(11) NOT NULL,
  Punto_Recoleccion_idPunto_Recoleccion INT(11) NOT NULL,
  PRIMARY KEY (Residuo_idResiduo, Punto_Recoleccion_idPunto_Recoleccion),
  KEY fk_Punto (Punto_Recoleccion_idPunto_Recoleccion),
  KEY fk_Residuo (Residuo_idResiduo),
  CONSTRAINT punto_residuo_ibfk_1 FOREIGN KEY (Residuo_idResiduo) REFERENCES residuo (idResiduo),
  CONSTRAINT punto_residuo_ibfk_2 FOREIGN KEY (Punto_Recoleccion_idPunto_Recoleccion) REFERENCES punto_recoleccion (idPunto_Recoleccion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla solicitud_recoleccion
CREATE TABLE solicitud_recoleccion (
  idSolicitud_Recoleccion INT(11) NOT NULL AUTO_INCREMENT,
  direccion VARCHAR(200) NOT NULL,
  fecha_solicitud DATE NOT NULL,
  fecha_programada DATETIME DEFAULT NULL,
  estado VARCHAR(45) NOT NULL,
  Usuario_idUsuario INT(11) NOT NULL,
  Residuo_idResiduo INT(11) NOT NULL,
  Colaborador_idColaborador INT(11) NOT NULL,
  PRIMARY KEY (idSolicitud_Recoleccion),
  KEY Usuario_idUsuario (Usuario_idUsuario),
  KEY Residuo_idResiduo (Residuo_idResiduo),
  KEY Colaborador_idColaborador (Colaborador_idColaborador),
  CONSTRAINT solicitud_recoleccion_ibfk_1 FOREIGN KEY (Usuario_idUsuario) REFERENCES usuario (idUsuario),
  CONSTRAINT solicitud_recoleccion_ibfk_2 FOREIGN KEY (Residuo_idResiduo) REFERENCES residuo (idResiduo),
  CONSTRAINT solicitud_recoleccion_ibfk_3 FOREIGN KEY (Colaborador_idColaborador) REFERENCES colaborador (idColaborador)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

COMMIT;
