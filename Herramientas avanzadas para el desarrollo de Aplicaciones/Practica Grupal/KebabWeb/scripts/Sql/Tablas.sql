-- Creación de la tabla Empresa
CREATE TABLE Empresa (
    CIF VARCHAR(9) PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL,
    direccion VARCHAR(100) NOT NULL,
    ciudad VARCHAR(30) NOT NULL,
    telefono VARCHAR(9) NOT NULL,
    email VARCHAR(30) NOT NULL UNIQUE
);


-- Creación de la tabla Oferta
CREATE TABLE Oferta (
    id INT PRIMARY KEY,
    precio DECIMAL(4, 2) NOT NULL,
    codigo varchar(30) NOT NULL,
);

-- Creación de la tabla Producto
CREATE TABLE Producto (
    id INT PRIMARY KEY,
    nombre VARCHAR(20) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(4, 2) NOT NULL,
    imagen VARCHAR(50),
    tipo_producto VARCHAR(15) NOT NULL CHECK (tipo_producto IN ('Entrantes', 'Kebab', 'Postres', 'Bebidas'))
);

-- Creación de la tabla Usuario
CREATE TABLE Usuario (
    email VARCHAR(30) PRIMARY KEY,
    contraseña VARCHAR(30) NOT NULL,
    nombre CHAR(30) NOT NULL,
    dni VARCHAR(9) UNIQUE NOT NULL,
    tipo_usuario VARCHAR(15) NOT NULL CHECK (tipo_usuario IN ('Empleado', 'Cliente', 'Administrador')),
);

-- Creación de la tabla Local
CREATE TABLE Locales (
    id INT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL,
    direccion VARCHAR(100) NOT NULL,
    ciudad VARCHAR(30) NOT NULL,
    supervisor varchar(30),
    telefono VARCHAR(9) NOT NULL,
    ubicacion VARCHAR(350)
);

-- Creación de la tabla Empleado
CREATE TABLE Empleado (
    email VARCHAR(30) PRIMARY KEY,
    salario DECIMAL(6, 2) NOT NULL,
    trabaja_en_local_id INT,
    FOREIGN KEY (email) REFERENCES Usuario(email) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (trabaja_en_local_id) REFERENCES Locales(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Creación de la tabla Cliente
CREATE TABLE Cliente (
    email VARCHAR(30) PRIMARY KEY,
    FOREIGN KEY (email) REFERENCES Usuario(email) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Creación de la tabla Administrador
CREATE TABLE Administrador (
    email VARCHAR(30) PRIMARY KEY,
    FOREIGN KEY (email) REFERENCES Usuario(email) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Creación de la tabla Mensaje
CREATE TABLE Mensaje (
    id INT PRIMARY KEY,
    mensaje TEXT NOT NULL,
    tipo_mensaje VARCHAR(12) NOT NULL CHECK (tipo_mensaje IN ('Incidencia', 'Comentario')),
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Creación de la tabla Incidencia
CREATE TABLE Incidencia (
    mensaje_id INT PRIMARY KEY,
    FOREIGN KEY (mensaje_id) REFERENCES Mensaje(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Creación de la tabla Comentario
CREATE TABLE Comentario (
    mensaje_id INT PRIMARY KEY,
    valoracion INT DEFAULT 0,
    FOREIGN KEY (mensaje_id) REFERENCES Mensaje(id) ON DELETE CASCADE ON UPDATE CASCADE
);


-- Creación de la tabla Local_Mensaje_Cliente
CREATE TABLE Local_Mensaje_Usuario (
    local_id INT NOT NULL,
    mensaje_id INT NOT NULL,
    usuario_email VARCHAR(30) NOT NULL,
    PRIMARY KEY (local_id, mensaje_id),
    FOREIGN KEY (local_id) REFERENCES Locales(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (mensaje_id) REFERENCES Mensaje(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (usuario_email) REFERENCES Usuario(email) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE (usuario_email, mensaje_id)
);

CREATE TABLE Menu(
    id int,
    nombre varchar(40),
    precio float,
    primary key (id, nombre),
    id_prod1 int,
    id_prod2 int,
    id_prod3 int,
    FOREIGN KEY (id_prod1) REFERENCES Producto(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (id_prod2) REFERENCES Producto(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (id_prod3) REFERENCES Producto(id) ON DELETE NO ACTION ON UPDATE NO ACTION
);


-- Creación de la tabla Pedido
CREATE TABLE Pedido (
    id INT PRIMARY KEY,
    cliente_email VARCHAR(30) NOT NULL,
    cantidad int not null,
    local_id int not null,
    total float null,
    fecha_pedido DATE NOT NULL,
    FOREIGN KEY (cliente_email) REFERENCES Cliente(email) ON DELETE CASCADE ON UPDATE CASCADE,
    foreign key (local_id) references Locales(id) on delete no action on update cascade,
    estado VARCHAR(10) NOT NULL CHECK (estado IN ('Preparado', 'En curso', 'En reparto')),
);

-- Creación de la tabla Albaran
CREATE TABLE Albaran (
    idA INT,
    idP INT,
    PRIMARY KEY (idA, idP),
    fecha DATE NOT NULL,
    importe DECIMAL(9, 2) NOT NULL,
    FOREIGN KEY (idP) REFERENCES Pedido(id) ON DELETE NO ACTION ON UPDATE CASCADE
);

-- Creación de la tabla Pedido_menu
CREATE TABLE Pedido_menu (
    pedido_id INT NOT NULL,
    menu_id INT NOT NULL,
    cantidad INT NOT NULL,
    PRIMARY KEY (pedido_id, menu_id),
    FOREIGN KEY (pedido_id) REFERENCES Pedido(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Creación de la tabla Pedido_prod
CREATE TABLE Pedido_prod (
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    PRIMARY KEY (pedido_id, producto_id),
    FOREIGN KEY (pedido_id) REFERENCES Pedido(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES Producto(id) ON DELETE NO ACTION ON UPDATE NO ACTION
);


alter table Locales add foreign key(supervisor) references empleado(email) on delete no action on update no action;




