--
-- PostgreSQL database dump
--

-- Started on 2012-01-20 09:22:59

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- TOC entry 449 (class 2612 OID 16386)
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: -; Owner: -
--

CREATE PROCEDURAL LANGUAGE plpgsql;


SET search_path = public, pg_catalog;

--
-- TOC entry 127 (class 1259 OID 16735)
-- Dependencies: 3
-- Name: auditorias_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE auditorias_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1807 (class 0 OID 0)
-- Dependencies: 127
-- Name: auditorias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('auditorias_id_seq', 9, true);


SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 135 (class 1259 OID 16926)
-- Dependencies: 1773 3
-- Name: auditorias; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE auditorias (
    id integer DEFAULT nextval('auditorias_id_seq'::regclass) NOT NULL,
    usuarios_id integer NOT NULL,
    fecha_at date,
    accion_realizada text NOT NULL,
    tabla_afectada character varying
);


--
-- TOC entry 134 (class 1259 OID 16924)
-- Dependencies: 135 3
-- Name: auditorias_id_seq1; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE auditorias_id_seq1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1808 (class 0 OID 0)
-- Dependencies: 134
-- Name: auditorias_id_seq1; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE auditorias_id_seq1 OWNED BY auditorias.id;


--
-- TOC entry 1809 (class 0 OID 0)
-- Dependencies: 134
-- Name: auditorias_id_seq1; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('auditorias_id_seq1', 2, true);


--
-- TOC entry 137 (class 1259 OID 16942)
-- Dependencies: 1775 1776 3
-- Name: menus; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE menus (
    id integer NOT NULL,
    menus_id integer,
    recursos_id integer NOT NULL,
    nombre character varying(100) NOT NULL,
    url character varying(100) NOT NULL,
    posicion integer DEFAULT 100,
    clases character varying(50),
    activo integer DEFAULT 1 NOT NULL,
    visible_en integer DEFAULT 2
);


--
-- TOC entry 136 (class 1259 OID 16940)
-- Dependencies: 137 3
-- Name: menus_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE menus_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1810 (class 0 OID 0)
-- Dependencies: 136
-- Name: menus_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE menus_id_seq OWNED BY menus.id;


--
-- TOC entry 1811 (class 0 OID 0)
-- Dependencies: 136
-- Name: menus_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('menus_id_seq', 9, true);


--
-- TOC entry 129 (class 1259 OID 16813)
-- Dependencies: 1768 3
-- Name: recursos; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE recursos (
    id integer NOT NULL,
    modulo character varying(50),
    controlador character varying(50) NOT NULL,
    accion character varying,
    recurso character varying NOT NULL,
    descripcion text NOT NULL,
    activo integer DEFAULT 1 NOT NULL
);


--
-- TOC entry 128 (class 1259 OID 16811)
-- Dependencies: 129 3
-- Name: recursos_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE recursos_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1812 (class 0 OID 0)
-- Dependencies: 128
-- Name: recursos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE recursos_id_seq OWNED BY recursos.id;


--
-- TOC entry 1813 (class 0 OID 0)
-- Dependencies: 128
-- Name: recursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('recursos_id_seq', 9, true);


--
-- TOC entry 131 (class 1259 OID 16825)
-- Dependencies: 1770 3
-- Name: roles; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE roles (
    id integer NOT NULL,
    rol character varying(50) NOT NULL,
    padres character varying(20),
    plantilla character varying(50),
    activo integer DEFAULT 1 NOT NULL
);


--
-- TOC entry 130 (class 1259 OID 16823)
-- Dependencies: 3 131
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1814 (class 0 OID 0)
-- Dependencies: 130
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE roles_id_seq OWNED BY roles.id;


--
-- TOC entry 1815 (class 0 OID 0)
-- Dependencies: 130
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('roles_id_seq', 1, false);


--
-- TOC entry 139 (class 1259 OID 16966)
-- Dependencies: 3
-- Name: roles_recursos; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE roles_recursos (
    id integer NOT NULL,
    roles_id integer NOT NULL,
    recursos_id integer NOT NULL
);


--
-- TOC entry 138 (class 1259 OID 16964)
-- Dependencies: 139 3
-- Name: roles_recursos_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE roles_recursos_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1816 (class 0 OID 0)
-- Dependencies: 138
-- Name: roles_recursos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE roles_recursos_id_seq OWNED BY roles_recursos.id;


--
-- TOC entry 1817 (class 0 OID 0)
-- Dependencies: 138
-- Name: roles_recursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('roles_recursos_id_seq', 38, true);


--
-- TOC entry 133 (class 1259 OID 16852)
-- Dependencies: 1772 3
-- Name: usuarios; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE usuarios (
    id integer NOT NULL,
    login character varying(50) NOT NULL,
    clave character varying(40) NOT NULL,
    nombres character varying(100) NOT NULL,
    email character varying(100) NOT NULL,
    roles_id integer NOT NULL,
    activo integer DEFAULT 1 NOT NULL
);


--
-- TOC entry 132 (class 1259 OID 16850)
-- Dependencies: 3 133
-- Name: usuarios_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE usuarios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1818 (class 0 OID 0)
-- Dependencies: 132
-- Name: usuarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE usuarios_id_seq OWNED BY usuarios.id;


--
-- TOC entry 1819 (class 0 OID 0)
-- Dependencies: 132
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('usuarios_id_seq', 1, false);


--
-- TOC entry 1774 (class 2604 OID 16945)
-- Dependencies: 136 137 137
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE menus ALTER COLUMN id SET DEFAULT nextval('menus_id_seq'::regclass);


--
-- TOC entry 1767 (class 2604 OID 16816)
-- Dependencies: 128 129 129
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE recursos ALTER COLUMN id SET DEFAULT nextval('recursos_id_seq'::regclass);


--
-- TOC entry 1769 (class 2604 OID 16828)
-- Dependencies: 131 130 131
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE roles ALTER COLUMN id SET DEFAULT nextval('roles_id_seq'::regclass);


--
-- TOC entry 1777 (class 2604 OID 16969)
-- Dependencies: 138 139 139
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE roles_recursos ALTER COLUMN id SET DEFAULT nextval('roles_recursos_id_seq'::regclass);


--
-- TOC entry 1771 (class 2604 OID 16855)
-- Dependencies: 132 133 133
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE usuarios ALTER COLUMN id SET DEFAULT nextval('usuarios_id_seq'::regclass);


--
-- TOC entry 1799 (class 0 OID 16926)
-- Dependencies: 135
-- Data for Name: auditorias; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO auditorias (id, usuarios_id, fecha_at, accion_realizada, tabla_afectada) VALUES (1, 1, '2012-01-19', 'Editó la Configuración de la aplicación', 'ARCHIVO CONFIG.INI');
INSERT INTO auditorias (id, usuarios_id, fecha_at, accion_realizada, tabla_afectada) VALUES (2, 1, '2012-01-19', 'Colocó al usuario admin como inactivo', 'USUARIOS');
INSERT INTO auditorias (id, usuarios_id, fecha_at, accion_realizada, tabla_afectada) VALUES (3, 1, '2012-01-19', 'Colocó al usuario admin como inactivo', 'USUARIOS');
INSERT INTO auditorias (id, usuarios_id, fecha_at, accion_realizada, tabla_afectada) VALUES (4, 1, '2012-01-19', 'Colocó al usuario admin como activo', 'USUARIOS');
INSERT INTO auditorias (id, usuarios_id, fecha_at, accion_realizada, tabla_afectada) VALUES (5, 1, '2012-01-19', 'Editó al usuario admin', 'USUARIOS');
INSERT INTO auditorias (id, usuarios_id, fecha_at, accion_realizada, tabla_afectada) VALUES (6, 1, '2012-01-20', 'Agrego Nuevos Recursos al Sistema', 'RECURSOS');
INSERT INTO auditorias (id, usuarios_id, fecha_at, accion_realizada, tabla_afectada) VALUES (7, 1, '2012-01-20', 'Agregó el Menú Roles al sistema', 'MENU');
INSERT INTO auditorias (id, usuarios_id, fecha_at, accion_realizada, tabla_afectada) VALUES (8, 1, '2012-01-20', 'Agrego Nuevos Recursos al Sistema', 'RECURSOS');
INSERT INTO auditorias (id, usuarios_id, fecha_at, accion_realizada, tabla_afectada) VALUES (9, 1, '2012-01-20', 'Eliminó al Recurso admin/auditorias/resultados_usuario del Sistema', 'RECURSOS');


--
-- TOC entry 1800 (class 0 OID 16942)
-- Dependencies: 137
-- Data for Name: menus; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO menus (id, menus_id, recursos_id, nombre, url, posicion, clases, activo) VALUES (3, NULL, 1, 'Administración', 'admin/usuarios', 100, ' ', 1);
INSERT INTO menus (id, menus_id, recursos_id, nombre, url, posicion, clases, activo) VALUES (4, NULL, 1, 'Perfil', 'admin/usuarios/perfil', 10, NULL, 1);
INSERT INTO menus (id, menus_id, recursos_id, nombre, url, posicion, clases, activo) VALUES (5, 3, 3, 'Recursos', 'admin/recursos', 100, NULL, 1);
INSERT INTO menus (id, menus_id, recursos_id, nombre, url, posicion, clases, activo) VALUES (6, 3, 6, 'Privilegios', 'admin/privilegios', 100, NULL, 1);
INSERT INTO menus (id, menus_id, recursos_id, nombre, url, posicion, clases, activo) VALUES (1, 3, 1, 'Usuarios', 'admin/usuarios', 100, ' ', 1);
INSERT INTO menus (id, menus_id, recursos_id, nombre, url, posicion, clases, activo) VALUES (2, 3, 2, 'Menus', 'admin/menu', 100, ' ', 1);
INSERT INTO menus (id, menus_id, recursos_id, nombre, url, posicion, clases, activo) VALUES (7, 3, 4, 'Auditorias', 'admin/auditorias', 100, NULL, 1);
INSERT INTO menus (id, menus_id, recursos_id, nombre, url, posicion, clases, activo) VALUES (8, 3, 5, 'Conf. Aplicación', 'admin/index', 100, NULL, 1);
INSERT INTO menus (id, menus_id, recursos_id, nombre, url, posicion, clases, activo) VALUES (9, 3, 8, 'Roles', 'admin/roles', 100, NULL, 1);


--
-- TOC entry 1796 (class 0 OID 16813)
-- Dependencies: 129
-- Data for Name: recursos; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO recursos (id, modulo, controlador, accion, recurso, descripcion, activo) VALUES (1, 'admin', 'usuarios', NULL, 'admin/usuarios/*', 'modulod de usuarios', 1);
INSERT INTO recursos (id, modulo, controlador, accion, recurso, descripcion, activo) VALUES (2, 'admin', 'menu', NULL, 'admin/menu/*', ' dfsdfd', 1);
INSERT INTO recursos (id, modulo, controlador, accion, recurso, descripcion, activo) VALUES (3, 'admin', 'recursos', NULL, 'admin/recursos/*', ' ', 1);
INSERT INTO recursos (id, modulo, controlador, accion, recurso, descripcion, activo) VALUES (4, 'admin', 'auditorias', NULL, 'admin/auditorias/*', 'modulo de auditorias', 1);
INSERT INTO recursos (id, modulo, controlador, accion, recurso, descripcion, activo) VALUES (5, 'admin', 'index', NULL, 'admin/index/*', 'modulo de administracion', 1);
INSERT INTO recursos (id, modulo, controlador, accion, recurso, descripcion, activo) VALUES (6, 'admin', 'privilegios', NULL, 'admin/privilegios/*', 'menu de privilegios', 1);
INSERT INTO recursos (id, modulo, controlador, accion, recurso, descripcion, activo) VALUES (7, 'admin', 'recursos', 'editar', 'admin/recursos/editar', 'modulo de roles', 1);
INSERT INTO recursos (id, modulo, controlador, accion, recurso, descripcion, activo) VALUES (8, 'admin', 'roles', NULL, 'admin/roles/*', 'modulo roles', 1);


--
-- TOC entry 1797 (class 0 OID 16825)
-- Dependencies: 131
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO roles (id, rol, padres, plantilla, activo) VALUES (1, 'administrador', NULL, NULL, 1);


--
-- TOC entry 1801 (class 0 OID 16966)
-- Dependencies: 139
-- Data for Name: roles_recursos; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO roles_recursos (id, roles_id, recursos_id) VALUES (31, 1, 4);
INSERT INTO roles_recursos (id, roles_id, recursos_id) VALUES (32, 1, 5);
INSERT INTO roles_recursos (id, roles_id, recursos_id) VALUES (33, 1, 2);
INSERT INTO roles_recursos (id, roles_id, recursos_id) VALUES (34, 1, 6);
INSERT INTO roles_recursos (id, roles_id, recursos_id) VALUES (35, 1, 3);
INSERT INTO roles_recursos (id, roles_id, recursos_id) VALUES (36, 1, 7);
INSERT INTO roles_recursos (id, roles_id, recursos_id) VALUES (37, 1, 8);
INSERT INTO roles_recursos (id, roles_id, recursos_id) VALUES (38, 1, 1);


--
-- TOC entry 1798 (class 0 OID 16852)
-- Dependencies: 133
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO usuarios (id, login, clave, nombres, email, roles_id, activo) VALUES (1, 'admin', '202cb962ac59075b964b07152d234b70', 'manuel aguirre', 'programador.manuel@gmail.com', 1, 1);


--
-- TOC entry 1785 (class 2606 OID 16934)
-- Dependencies: 135 135
-- Name: auditorias_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY auditorias
    ADD CONSTRAINT auditorias_pkey PRIMARY KEY (id);


--
-- TOC entry 1787 (class 2606 OID 16949)
-- Dependencies: 137 137
-- Name: menus_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY menus
    ADD CONSTRAINT menus_pkey PRIMARY KEY (id);


--
-- TOC entry 1779 (class 2606 OID 16822)
-- Dependencies: 129 129
-- Name: recursos_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY recursos
    ADD CONSTRAINT recursos_pkey PRIMARY KEY (id);


--
-- TOC entry 1781 (class 2606 OID 16831)
-- Dependencies: 131 131
-- Name: roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- TOC entry 1789 (class 2606 OID 16971)
-- Dependencies: 139 139
-- Name: roles_recursos_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY roles_recursos
    ADD CONSTRAINT roles_recursos_pkey PRIMARY KEY (id);


--
-- TOC entry 1783 (class 2606 OID 16858)
-- Dependencies: 133 133
-- Name: usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- TOC entry 1791 (class 2606 OID 16935)
-- Dependencies: 135 1782 133
-- Name: auditorias_usuarios_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY auditorias
    ADD CONSTRAINT auditorias_usuarios_id_fkey FOREIGN KEY (usuarios_id) REFERENCES usuarios(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 1792 (class 2606 OID 16950)
-- Dependencies: 137 137 1786
-- Name: menus_menus_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY menus
    ADD CONSTRAINT menus_menus_id_fkey FOREIGN KEY (menus_id) REFERENCES menus(id) ON DELETE RESTRICT;


--
-- TOC entry 1793 (class 2606 OID 16955)
-- Dependencies: 137 1778 129
-- Name: menus_recursos_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY menus
    ADD CONSTRAINT menus_recursos_id_fkey FOREIGN KEY (recursos_id) REFERENCES recursos(id) ON DELETE RESTRICT;


--
-- TOC entry 1795 (class 2606 OID 16977)
-- Dependencies: 129 1778 139
-- Name: roles_recursos_recursos_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY roles_recursos
    ADD CONSTRAINT roles_recursos_recursos_id_fkey FOREIGN KEY (recursos_id) REFERENCES recursos(id) ON DELETE RESTRICT;


--
-- TOC entry 1794 (class 2606 OID 16972)
-- Dependencies: 1780 139 131
-- Name: roles_recursos_roles_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY roles_recursos
    ADD CONSTRAINT roles_recursos_roles_id_fkey FOREIGN KEY (roles_id) REFERENCES roles(id) ON DELETE RESTRICT;


--
-- TOC entry 1790 (class 2606 OID 16859)
-- Dependencies: 133 1780 131
-- Name: usuarios_roles_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_roles_id_fkey FOREIGN KEY (roles_id) REFERENCES roles(id) ON DELETE RESTRICT;


--
-- TOC entry 1806 (class 0 OID 0)
-- Dependencies: 3
-- Name: public; Type: ACL; Schema: -; Owner: -
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2012-01-20 09:23:00

--
-- PostgreSQL database dump complete
--

