-- Insertar datos en la tabla Empresa
INSERT INTO Empresa VALUES ('A12345678', 'Empresa1', 'Carrer de Porticons, 33, 03112 Alicante', 'Alicante', '912345678', 'empresa1@gmail.com');

INSERT INTO Empresa VALUES ('B12345678', 'Empresa2', 'Carrer Economista Bernacer, 11, 03011 Alacant, Alicante', 'Alicante', '912345679', 'empresa2@gmail.com');

INSERT INTO Empresa VALUES ('C12345678', 'Empresa3', 'Av. Sevilla, 7, 03690 Sant Vicent del Raspeig, Alicante', 'Alicante', '912345670', 'empresa3@gmail.com');

INSERT INTO Empresa VALUES ('D12345678', 'Empresa4', 'Carrer lo Torrent, 5, 03690 Sant Vicent del Raspeig, Alicante', 'Alicante', '912345671', 'empresa4@gmail.com');

INSERT INTO Empresa VALUES ('E12345678', 'Empresa5', 'C/ Alicante, 84, bajo 2, 03690 san vicente del raspaig, Alicante', 'Alicante', '912345672', 'empresa5@gmail.com');

-- Insertar datos en la tabla Oferta
INSERT INTO Oferta VALUES (1, 10.00, 'AhZwgKxC');
INSERT INTO Oferta VALUES (2, 20.00, 'Ajsifbn4');


-- Insertar datos en la tabla Producto

-- Insertar Entrantes
INSERT INTO Producto VALUES (1, 'Croquetas', 'Croquetas de pollo recien horneadas', 3.50, '/assets/images/Entrantes/Croquetas.jpg', 'Entrantes');
INSERT INTO Producto VALUES (2, 'Emapanadillas', 'Empanadillas con relleno de kebab al mas puro estilo turco', 3.50, '/assets/images/Entrantes/Empanadillas.jpg', 'Entrantes');
INSERT INTO Producto VALUES (3, 'Bolas de Queso', 'Racion de bolas de mozzarela fritos en nuestra freidora, para los fans del queso!', 2.50, '/assets/images/Entrantes/BolasQueso.jpg', 'Entrantes');
INSERT INTO Producto VALUES (4, 'Falaffel', 'Clásico entrante turco hecho de garbanzo y verduras fritas', 1.50, '/assets/images/Entrantes/Falaffel.jpg', 'Entrantes');
INSERT INTO Producto VALUES (5, 'Fingers de Pollo', 'Tiras de pollo fritas super crujientes, racion para dos.', 6.50, '/assets/images/Entrantes/FingersPollo.jpg', 'Entrantes');
INSERT INTO Producto VALUES (6, 'Nuggets', '10 piezas de pechuga de pollo fritas con ketchup y nuestra salsa especial de dippeo.', 4.50, '/assets/images/Entrantes/Nuggets.jpg', 'Entrantes');
INSERT INTO Producto VALUES (7, 'Patatas Deluxe', 'Racion mediana de patatas de gajo deluxe con salsa ranchera para complementar.', 2.50, '/assets/images/Entrantes/PatatasDeluxe.jpg', 'Entrantes');
INSERT INTO Producto VALUES (8, 'Patatas Fritas', 'Racion mediana de patatas fritas clasicas perfectas para acompañar tu kebab.', 1.99, '/assets/images/Entrantes/PatatasFritas.jpg', 'Entrantes');

-- Insertar Kebabs
INSERT INTO Producto VALUES (9, 'Durum', 'Nuestro plato estrella, el clasico durum relleno de verduras y nuestra exquisita carne', 4.50, '/assets/images/Platos/Durum.jpg', 'Kebab');
INSERT INTO Producto VALUES (10, 'Pan de Pita', 'Pan de pita relleno de verduras, falaffel carne y mucha salsa.', 3.99, '/assets/images/Platos/Pita.jpg', 'Kebab');
INSERT INTO Producto VALUES (11, 'Plato de Arroz', 'Plato de arroz, verduras y un monton de carne de ternera y pollo, ideal para compartir.', 6.99, '/assets/images/Platos/PlatoArroz.jpg', 'Kebab');
INSERT INTO Producto VALUES (12, 'Plato Kebab', 'Plato de carne de kebab, verduras y una racion de patatas cuibertas en salsa', 5.99, '/assets/images/Platos/PlatoKebab.jpeg', 'Kebab');
INSERT INTO Producto VALUES (13, 'Pizza Kebab', 'La clasica pizza italiana pero con mucha carne de kebab como ingrediente.', 7.99, '/assets/images/Platos/Pizza.jpg', 'Kebab');
INSERT INTO Producto VALUES (14, 'Pizza Turca', 'Masa de pizza con una capa de falaffel, carne de kebab, verduras y mucha salsa enrollado en forma de durum.', 4.99, '/assets/images/Platos/pizza-turca.jpg', 'Kebab');

-- Insertar Bebidas
INSERT INTO Producto VALUES (15, 'Freeway Cola', 'Refresco de Cola 33cl.', 1.50, '/assets/images/Bebidas/Cola.jpg', 'Bebidas');
INSERT INTO Producto VALUES (16, 'Freeway Fanta', 'Refresco de Naranja 33cl.', 1.50, '/assets/images/Bebidas/Fanta.jpg', 'Bebidas');
INSERT INTO Producto VALUES (17, 'Sprite', 'Lata de Sprite sabor lima limón de 33cl.', 1.99, '/assets/images/Bebidas/Sprite.jpg', 'Bebidas');
INSERT INTO Producto VALUES (18, 'Agua', 'Botella de Agua mineral de 50cl.', 1.50, '/assets/images/Bebidas/Agua.jpg', 'Bebidas');
INSERT INTO Producto VALUES (19, 'Cerveza', 'Lata de Cerveza clásica de Steinburg.', 1.99, '/assets/images/Bebidas/Cerveza.jpg', 'Bebidas');
INSERT INTO Producto VALUES (20, 'Cubata', 'Cubatita bien cargado para hacer la previa con nosotros!', 3.99, '/assets/images/Bebidas/Cubata.jpeg', 'Bebidas');

-- Insertar Postres
INSERT INTO Producto VALUES (21, 'Tulumba', 'Postre turco clasico hecho con harina con dulce de leche como dippeo.', 1.99, '/assets/images/Postres/Tulumba.jpg', 'Postres');
INSERT INTO Producto VALUES (22, 'Baklava', 'Milenario postre de los turcos hecho de hojaldre y nueces turcas.', 1.50, '/assets/images/Postres/Baklava.jpg', 'Postres');
INSERT INTO Producto VALUES (23, 'Delicias Turcas', 'Delicias turcas perfectas para acompañar nuestro té.', 0.99, '/assets/images/Postres/DeliciasTurcas.jpg', 'Postres');
INSERT INTO Producto VALUES (24, 'Helado', 'Helado de chocolate perfecto para el verano.', 2.50, '/assets/images/Postres/Helado.jpg', 'Postres');
INSERT INTO Producto VALUES (25, 'Té Turco', 'Té clasico turco para bajar las comidas, cuidado, es muy amargo!', 0.50, '/assets/images/Postres/TeTurco.jpg', 'Postres');
INSERT INTO Producto VALUES (26, 'Café', 'Taza de café turco tostado muy amargo, preparado tradicionalmente.', 0.99, '/assets/images/Postres/Cafe.jpg', 'Postres');

-- Insertar Menus
INSERT INTO Menu VALUES (1, 'Durum + Patatas Deluxe + Cola', 6.99, 9, 7, 15);
INSERT INTO Menu VALUES (2, 'Pizza + Bolas de Queso + Fanta', 7.50, 14, 3, 16);
INSERT INTO Menu VALUES (3, 'Patatas Fritas y Deluxe + Cerveza', 4.99, 8, 7, 19);
INSERT INTO Menu VALUES (4, 'Pizza Kebab + Té + Delicias Turcas', 7.99, 13, 25, 23);
INSERT INTO Menu VALUES (5, 'Menu Durum + Pita + Empanadillas', 9.99, 9, 10, 2);

-- Cuentas especiales para ti ticher
INSERT INTO Usuario VALUES ('sergiogarcia@gmail.com', 'lesvoyaponerun10', 'sergiogarcia', 'A1234567A', 'Cliente');
INSERT INTO Usuario VALUES ('sergiogod@gmail.com', 'locuradepagina', 'sergiogod', 'B1234567B', 'Administrador');
INSERT INTO Usuario VALUES ('sergioatrabajar@gmail.com', 'salariominimo', 'sergioatrabajar', 'C1234567C', 'Empleado');

-- Insertar datos en la tabla Usuario
INSERT INTO Usuario VALUES ('usuario1@gmail.com', 'Contraseña1', 'Usuario1', '12345678A', 'Cliente');
INSERT INTO Usuario VALUES ('usuario2@gmail.com', 'Contraseña2', 'Usuario2', '12345678B', 'Cliente');
INSERT INTO Usuario VALUES ('usuario3@gmail.com', 'Contraseña3', 'Usuario3', '12345678C', 'Cliente');
INSERT INTO Usuario VALUES ('usuario4@gmail.com', 'Contraseña4', 'Usuario4', '12345678D', 'Cliente');
INSERT INTO Usuario VALUES ('usuario5@gmail.com', 'Contraseña5', 'Usuario5', '12345678E', 'Cliente');
INSERT INTO Usuario VALUES ('admin@kebab.com', 'admin123', 'Admin1', '12345678F', 'Administrador');
INSERT INTO Usuario VALUES ('empleado@gmail.com', 'empleado2', 'Empleado2', '12345678G', 'Empleado');
INSERT INTO Usuario VALUES ('empleado2@gmail.com', 'empleado2', 'Empleado2', '12345678H', 'Empleado');
INSERT INTO Usuario VALUES ('empleado3@gmail.com', 'empleado2', 'Empleado2', '12345678I', 'Empleado');



 -- Insertar datos en la tabla Locales
INSERT INTO Locales VALUES (1, 'Kebab Corner', 'Calle Alicante, 84, San Vicente del Raspeig, 03690', 'Madrid', null, '231-27-44', 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d25017.543737153814!2d-0.5534691!3d38.3907871!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd6236b03b9c640f%3A0x1d6561560c83bd61!2sKebab%20Corner!5e0!3m2!1ses!2ses!4v1685115766728!5m2!1ses!2ses');
INSERT INTO Locales VALUES (2, 'Doner Kebab Egipto', 'Calle de Rafael Asín, 25, 03010 Alicante', 'Madrid', null, '309-64-92', 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d25024.52537509799!2d-0.526175!3d38.3706022!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd62370064ddf4e7%3A0x20f10ed912c9cdeb!2sDoner%20Kebap%20Egipto!5e0!3m2!1ses!2ses!4v1685115694409!5m2!1ses!2ses');
INSERT INTO Locales VALUES (3, 'Ali Kebab', 'Calle Espoz y Mina, 1, 03012 Alicante', 'Madrid', null, '139-40-53', 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d25024.52537509799!2d-0.526175!3d38.3706022!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd6237a9561cd45d%3A0x11d46c8bb0762eb4!2sAli%20Kebab!5e0!3m2!1ses!2ses!4v1685115936564!5m2!1ses!2ses');
INSERT INTO Locales VALUES (4, 'Turkish Kebab', 'Calle del Regidor Lorenzo Llaneras, 1, 03005 Alicante', 'Madrid', null, '986-63-58', 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d25024.52537509799!2d-0.526175!3d38.3706022!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd62365b2391f3ff%3A0x4fa84a4abecfc661!2sTurkish%20Gourmet%20-%20965%2012%2067%2041!5e0!3m2!1ses!2ses!4v1685116018806!5m2!1ses!2ses');
INSERT INTO Locales VALUES (5, 'Amigos Kebab', 'Avenida del Padre Espla, 49, 03013 Alicante', 'Madrid', null, '897-53-24', 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d25024.52537509799!2d-0.526175!3d38.3706022!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd6237f990aa972f%3A0x10a66ab70ff8b4c9!2sAmigos%20Kebab!5e0!3m2!1ses!2ses!4v1685116083495!5m2!1ses!2ses');


--Insertar datos en la tabla Cliente
INSERT INTO Cliente VALUES ('usuario1@gmail.com');
INSERT INTO Cliente VALUES ('usuario2@gmail.com');
INSERT INTO Cliente VALUES ('usuario3@gmail.com');
INSERT INTO Cliente VALUES ('usuario4@gmail.com');
INSERT INTO Cliente VALUES ('usuario5@gmail.com');


-- Insertar datos en la tabla Administrador
INSERT INTO Administrador VALUES ('admin@kebab.com');


--Insertar en empleado
insert into Empleado(email, salario, trabaja_en_local_id) values ('empleado@gmail.com', 1200.00, '1');
insert into Empleado(email, salario) values ('empleado2@gmail.com', 1200.00);
insert into Empleado(email, salario) values ('empleado3@gmail.com', 1200.00);

-- Insertar datos en la tabla Mensaje
-- Insertar datos en la tabla Mensaje
INSERT INTO Mensaje VALUES (1, 'Mensaje de incidencia 1', 'Incidencia', '2023-05-21');
INSERT INTO Mensaje VALUES (2, 'Mensaje de comentario 1', 'Comentario', '2023-05-22');
INSERT INTO Mensaje VALUES (3, 'Mensaje de incidencia 2', 'Incidencia', '2023-05-23');
INSERT INTO Mensaje VALUES (4, 'Mensaje de comentario 2', 'Comentario', '2023-05-24');
INSERT INTO Mensaje VALUES (5, 'Mensaje de incidencia 3', 'Incidencia', '2023-05-25');


-- Insertar datos en la tabla Incidencia
INSERT INTO Incidencia VALUES (1);
INSERT INTO Incidencia VALUES (3);
INSERT INTO Incidencia VALUES (5);

-- Insertar datos en la tabla Comentario
INSERT INTO Comentario VALUES (2,1);
INSERT INTO Comentario VALUES (4,1);

-- Suponiendo que existe una tabla de Locales con al menos un id válido
-- Insertar datos en la tabla Local_Mensaje_Cliente
INSERT INTO Local_Mensaje_Usuario VALUES (1, 1, 'usuario1@gmail.com');
INSERT INTO Local_Mensaje_Usuario VALUES (2, 2, 'usuario1@gmail.com');
INSERT INTO Local_Mensaje_Usuario VALUES (3, 3, 'usuario1@gmail.com');
INSERT INTO Local_Mensaje_Usuario VALUES (4, 4, 'usuario1@gmail.com');
INSERT INTO Local_Mensaje_Usuario VALUES (5, 5, 'usuario1@gmail.com');

-- Insertar datos en la tabla Pedido
INSERT INTO Pedido VALUES (1, 'usuario1@gmail.com',2,  1, 20.00, '2023-06-01', 'En curso');
INSERT INTO Pedido VALUES (2, 'usuario1@gmail.com',1, 1, 40.00, '2023-06-02', 'En curso');
INSERT INTO Pedido VALUES (3, 'usuario1@gmail.com',1, 2, 60.00, '2023-06-03', 'En curso');
INSERT INTO Pedido VALUES (4, 'usuario1@gmail.com',1, 1, 80.00, '2023-06-04', 'En curso');
INSERT INTO Pedido VALUES (5, 'usuario1@gmail.com',3, 2, 100.00, '2023-06-05', 'En curso');

-- Suponiendo que existe una tabla de Menu con al menos un id válido
-- Insertar datos en la tabla Pedido_menu
INSERT INTO Pedido_menu VALUES (1, 1, 2);
INSERT INTO Pedido_menu VALUES (2, 1, 2);
INSERT INTO Pedido_menu VALUES (3, 1, 2);
INSERT INTO Pedido_menu VALUES (4, 1, 2);
INSERT INTO Pedido_menu VALUES (5, 1, 2);

-- Insertar datos en la tabla Pedido_prod
INSERT INTO Pedido_prod VALUES (1, 1, 2);
INSERT INTO Pedido_prod VALUES (2, 2, 2);
INSERT INTO Pedido_prod VALUES (3, 3, 2);
INSERT INTO Pedido_prod VALUES (4, 4, 2);
INSERT INTO Pedido_prod VALUES (5, 5, 2);


-- Insertar datos en la tabla Albaran
INSERT INTO Albaran (idA, idP, fecha, importe)
VALUES (1, 1, '2023-05-01', 25.50);


INSERT INTO Albaran (idA, idP, fecha, importe)
VALUES (2, 1, '2023-05-02', 18.75);


INSERT INTO Albaran (idA, idP, fecha, importe)
VALUES (3, 1, '2023-05-03', 42.80);


INSERT INTO Albaran (idA, idP, fecha, importe)
VALUES (4, 1, '2023-05-04', 12.95);


INSERT INTO Albaran (idA, idP, fecha, importe)
VALUES (5, 1, '2023-05-05', 31.20);
