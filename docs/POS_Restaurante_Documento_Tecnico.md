# Sistema POS Web — Restaurante
## Documento Técnico v1.0

**Versión:** 1.0.0  
**Estado:** Borrador  
**Fecha:** Junio 2026  
**Stack:** Laravel 11 · Livewire 3 · MySQL 8 · Chart.js 4 · D3.js  
**Autor:** Equipo de Desarrollo

---

## Tabla de Contenidos

1. [Introducción y Alcance](#1-introducción-y-alcance)
2. [Roles y Usuarios](#2-roles-y-usuarios)
3. [Mapa General de Módulos](#3-mapa-general-de-módulos)
4. [Módulo 1 — POS / Sala](#4-módulo-1--pos--sala)
5. [Módulo 2 — Cocina (KDS)](#5-módulo-2--cocina-kds)
6. [Módulo 3 — Menú y Productos](#6-módulo-3--menú-y-productos)
7. [Módulo 4 — Caja y Pagos](#7-módulo-4--caja-y-pagos)
8. [Módulo 5 — Inventario](#8-módulo-5--inventario)
9. [Módulo 6 — Reportes y Analítica](#9-módulo-6--reportes-y-analítica)
10. [Módulo 7 — Usuarios y Roles](#10-módulo-7--usuarios-y-roles)
11. [Módulo 8 — Configuración](#11-módulo-8--configuración)
12. [Módulo 9 — Notificaciones](#12-módulo-9--notificaciones)
13. [Módulo 10 — WhatsApp Marketing & Chatbot](#13-módulo-10--whatsapp-marketing--chatbot)
8. [Módulo 5 — Inventario](#8-módulo-5--inventario)
9. [Módulo 6 — Reportes y Analítica](#9-módulo-6--reportes-y-analítica)
10. [Módulo 7 — Usuarios y Roles](#10-módulo-7--usuarios-y-roles)
11. [Módulo 8 — Configuración](#11-módulo-8--configuración)
12. [Módulo 9 — Notificaciones](#12-módulo-9--notificaciones)
13. [Arquitectura Técnica](#13-arquitectura-técnica)
14. [Esquema de Base de Datos](#14-esquema-de-base-de-datos)
15. [Flujo de Tiempo Real (KDS)](#15-flujo-de-tiempo-real-kds)
16. [Plan de Desarrollo por Fases](#16-plan-de-desarrollo-por-fases)
17. [Seguridad y Rendimiento](#17-seguridad-y-rendimiento)
18. [Compatibilidad y Dispositivos](#18-compatibilidad-y-dispositivos)

---

## 1. Introducción y Alcance

Este documento describe la arquitectura completa, módulos funcionales, modelos de datos y criterios técnicos del **Sistema POS Web** para la administración integral de un restaurante.

El sistema digitaliza toda la operación del restaurante: desde la toma de comandas en sala hasta el cierre de caja, pasando por la cocina en tiempo real, el control de inventario y los reportes gerenciales. Está construido sobre **Laravel 11**, **Livewire 3**, **MySQL 8** y **Chart.js 4**, con soporte para múltiples roles, broadcast en tiempo real para el KDS y reportes exportables.

### 1.1 Objetivos del sistema

- Digitalizar la toma de comandas y el flujo de pedidos de sala a cocina
- Gestionar el menú, disponibilidad de productos e inventario de insumos
- Procesar pagos con múltiples métodos y emitir tickets / facturas electrónicas (SRI Ecuador)
- Proveer un KDS (Kitchen Display System) en tiempo real para el área de cocina
- Generar reportes de ventas, desempeño por mesero y análisis de productos
- Administrar usuarios con roles y permisos granulares mediante Spatie Permission

---

## 2. Roles y Usuarios

| Rol | Descripción | Módulos principales |
|-----|-------------|---------------------|
| **Administrador** | Control total del sistema | Todos los módulos |
| **Gerente** | Supervisión y análisis | Reportes, Inventario, Configuración |
| **Cajero** | Gestión de caja y cobros | POS, Caja, Pagos, Reportes básicos |
| **Mesero** | Toma de comandas en sala | Mesas, Comandas, Menú (lectura) |
| **Cocinero** | Gestión de pedidos en cocina | KDS Cocina |

---

## 3. Mapa General de Módulos

| # | Módulo | Sub-módulos clave | Prioridad |
|---|--------|-------------------|-----------|
| 1 | POS / Sala | Mesas, Comandas, Flujo de pedidos | Alta |
| 2 | Cocina (KDS) | Display comandas, Estados, Alertas | Alta |
| 3 | Menú y Productos | Categorías, Ítems, Modificadores, Disponibilidad | Alta |
| 4 | Caja y Pagos | Apertura/cierre, Métodos de pago, Tickets, SRI | Alta |
| 5 | Inventario | Stock, Movimientos, Recetas, Alertas | Media |
| 6 | Reportes | Ventas, Caja, Productos, Meseros, Dashboard | Media |
| 7 | Usuarios y Roles | CRUD, Permisos Spatie, Sesiones, PIN POS | Alta |
| 8 | Configuración | Restaurante, Impresoras, Impuestos, Turnos | Media |
| 9 | Notificaciones | Alertas internas, Email, WhatsApp | Baja |
| 10 | WhatsApp Marketing & Chatbot | Campañas, Catálogo, Chatbot, CRM de contactos | Alta |

---

## 4. Módulo 1 — POS / Sala

Es el núcleo del sistema. Permite gestionar el plano de mesas, tomar y modificar comandas, y enviarlas a cocina. Toda la interfaz es Livewire para reflejar cambios en tiempo real sin recarga de página.

### 4.1 Gestión de Mesas

- Vista visual del restaurante con representación de mesas: número, capacidad y estado
- **Estados de mesa:** `Disponible` · `Ocupada` · `Reservada` · `En cuenta` · `Bloqueada`
- Asignación de mesero responsable por mesa
- Indicador visual de tiempo transcurrido desde que se abrió la mesa
- Soporte para múltiples zonas: sala principal, terraza, barra, privado
- Agregar, unir o dividir mesas desde el panel de administración
- Reordenamiento del plano por drag & drop (editor de layout)

### 4.2 Comandas

- Crear nueva comanda para una mesa o para llevar (delivery / take away)
- Agregar ítems del menú con cantidad, modificadores y notas por ítem
- Enviar comanda parcial a cocina (solo los ítems listos para preparar)
- Editar comanda abierta: agregar, eliminar o cambiar cantidad de ítems
- Ver historial de envíos por comanda (quién agregó qué y cuándo)
- **Estados de comanda:** `Abierta` · `Enviada a cocina` · `Parcialmente lista` · `Completa` · `Cancelada`
- Cancelación de ítems con motivo (confirmación + campo de razón requerido)
- Transferir comanda de una mesa a otra
- Notas generales por mesa (alergias, celebración, preferencias)

### 4.3 Flujo de Pedido a Cocina

| Paso | Actor | Acción |
|------|-------|--------|
| 1 | Mesero | Selecciona mesa y abre comanda |
| 2 | Mesero | Agrega ítems con modificadores y notas |
| 3 | Mesero | Presiona "Enviar a cocina" |
| 4 | Sistema | Crea `order_items` con estado `pending`, emite evento `OrderSent` |
| 5 | KDS | Recibe evento por WebSocket, muestra comanda en pantalla |
| 6 | Cocinero | Marca ítems "en preparación" y luego "listo" |
| 7 | Sistema | Notifica al mesero cuando toda la comanda está lista |

### 4.4 Modo Para Llevar / Delivery

- Pedidos sin mesa asignada con datos del cliente (nombre, teléfono, dirección)
- Cola de pedidos delivery separada del flujo de sala
- Estados: `Recibido` · `En preparación` · `Listo para entrega` · `Entregado`

---

## 5. Módulo 2 — Cocina (KDS)

El Kitchen Display System es una pantalla de interacción para el personal de cocina. Se actualiza en tiempo real usando **Laravel Reverb** (WebSockets) con Livewire Echo. Alternativa simple: polling cada 5 segundos con `wire:poll`.

### 5.1 Funcionalidades del KDS

- Vista de tarjetas de comandas activas ordenadas por tiempo de llegada (FIFO)
- Cada tarjeta muestra: número de mesa, mesero responsable, lista de ítems, hora de envío, tiempo transcurrido
- Resaltado visual de comandas con más de N minutos en espera (N configurable por el admin)
- Marcar ítem individual como "en preparación" (amarillo) o "listo" (verde)
- Marcar toda la comanda como completa para notificar automáticamente al mesero
- Filtro por área de cocina: parrilla, frío, bebidas, postres
- Modo oscuro optimizado para pantallas de cocina con luz ambiental baja
- Historial del día: comandas completadas con tiempos promedio de preparación

### 5.2 Estados de Ítems en Cocina

| Estado | Color | Significado |
|--------|-------|-------------|
| Pendiente | Gris | Llegó a cocina, nadie la ha tomado |
| En preparación | Amarillo | El cocinero está trabajando en ella |
| Listo | Verde | El plato está listo para servir |
| Cancelado | Rojo | Ítem cancelado por el mesero |

### 5.3 Alertas del KDS

- Alerta sonora configurable al recibir una nueva comanda
- Parpadeo visual para comandas con tiempo de espera elevado
- Contador de comandas pendientes visible en el título del navegador (badge)
- Notificación push (opcional) al dispositivo del cocinero si usa PWA

---

## 6. Módulo 3 — Menú y Productos

### 6.1 Categorías

- CRUD de categorías con nombre, imagen, orden de aparición y estado activa/inactiva
- Sub-categorías opcionales (ej: Bebidas → Jugos, Cervezas, Vinos)
- Reordenamiento por drag & drop
- Disponibilidad por turno: mostrar categoría solo en ciertos horarios

### 6.2 Productos / Ítems del Menú

- CRUD completo: nombre, descripción, foto, precio base, categoría, código interno (SKU)
- Precios especiales: hora feliz, combos, precio para llevar
- **Disponibilidad manual:** el mesero puede marcarlo como agotado directamente desde el POS
- **Disponibilidad automática:** se desactiva si el stock de ingredientes llega a 0 (requiere receta)
- Tiempo estimado de preparación por producto (se muestra en el KDS)
- Etiquetas visuales: vegano, sin gluten, picante, popular, nuevo
- Activar / desactivar producto sin eliminarlo del historial de ventas

### 6.3 Modificadores y Extras

- Grupos de modificadores asociados a productos (ej: "Término de cocción", "Tamaño", "Extras")
- Tipo de grupo: elección única (radio), múltiple (checkbox) u obligatorio antes de añadir al carrito
- Cada opción puede tener costo adicional (o ser gratuita)
- Límites configurables: mínimo y máximo de opciones seleccionables por grupo

### 6.4 Combos y Promociones

- Crear combos que agrupan varios ítems a un precio especial
- Descuentos por porcentaje o valor fijo
- Vigencia por fechas y por horario del día
- Aplicación automática (si el cliente cumple la condición) o manual desde el POS
- Restricción por canal: solo para llevar, solo en sala, ambos

---

## 7. Módulo 4 — Caja y Pagos

### 7.1 Apertura y Cierre de Caja

- Apertura de turno con monto inicial en efectivo declarado por el cajero
- Cierre de caja con resumen detallado: ventas por método de pago, total esperado vs. total contado
- Diferencia positiva o negativa registrada con campo de observaciones obligatorio
- Historial de todos los cierres con exportación a PDF
- Un único turno abierto por caja a la vez; soporte para múltiples cajas simultáneas
- Arqueo de caja intermedio sin necesidad de cerrar el turno

### 7.2 Proceso de Cobro

- Calcular cuenta de una mesa con detalle de ítems y subtotales
- Aplicar descuentos: por porcentaje, valor fijo o código de descuento (voucher)
- Propina configurable: opciones sugeridas (10% / 15% / 20%) o monto libre
- **División de cuenta:** equitativa por número de personas o por ítems específicos seleccionados
- **Pago mixto:** combinar efectivo + tarjeta + transferencia en un mismo cobro
- Registro del cambio exacto en pagos con efectivo
- Pre-cuenta imprimible mientras la mesa sigue abierta (sin cerrarla)

### 7.3 Métodos de Pago

| Método | Integración requerida | Notas |
|--------|----------------------|-------|
| Efectivo | No | Calcula cambio automáticamente |
| Tarjeta crédito | Sí — pasarela | Kushki / DataFast / Stripe Terminal |
| Tarjeta débito | Sí — pasarela | Misma pasarela que crédito |
| Transferencia bancaria | No | Registro manual + número de comprobante |
| QR / Wallets | Sí — API | PayPhone, De Una (Ecuador) |
| Crédito interno | No | Cuenta de cliente frecuente, se salda después |

### 7.4 Tickets y Facturación

- **Ticket de consumo:** detalle de ítems, subtotal, IVA (15%), descuentos, propina, total
- **Factura electrónica SRI Ecuador:** generación de comprobantes XML firmados, envío al SRI y almacenamiento del RIDE (PDF/XML)
- Soporte para los tipos de comprobante: Factura, Nota de venta (RIMPE), Nota de crédito
- Impresión en impresora térmica por red mediante `escpos-php` o QZ Tray
- Envío de ticket digital por WhatsApp Business API o correo electrónico
- Formato personalizable: logo, datos del restaurante, leyenda de pie, número de mesa y mesero

---

## 8. Módulo 5 — Inventario

Módulo orientado a controlar el stock de insumos y vincularlos al despacho de comandas mediante recetas productivas.

### 8.1 Ingredientes e Insumos

- CRUD de ingredientes: nombre, unidad de medida, stock actual, stock mínimo, costo unitario
- Categorización: carnes, lácteos, bebidas, verduras, insumos de limpieza, etc.
- Historial completo de movimientos: entradas (compras), salidas (consumo por receta), ajustes manuales con responsable y motivo

### 8.2 Recetas

- Vincular un producto del menú a una lista de ingredientes con cantidades exactas
- Al procesar una comanda confirmada, el sistema descuenta automáticamente del stock
- Opción de desactivar el descuento automático producto por producto
- Costo de receta calculado automáticamente según el precio unitario de cada ingrediente
- Margen de ganancia estimado: precio de venta vs. costo de receta

### 8.3 Alertas de Stock

- Notificación interna cuando un ingrediente cae por debajo del stock mínimo configurado
- Panel de alertas activas en el dashboard del administrador/gerente
- Reporte de ingredientes críticos exportable a CSV/Excel

### 8.4 Compras y Proveedores

- CRUD de proveedores: nombre, RUC, teléfono, email, condiciones de pago
- Registro de órdenes de compra y recepción de mercadería (aumenta el stock)
- Historial de compras por proveedor con precios históricos para análisis de costos
- Comparativa de precios entre proveedores para el mismo ingrediente

---

## 9. Módulo 6 — Reportes y Analítica

Panel de reportes construido con **Chart.js 4** y **D3.js** para visualizaciones avanzadas. Todos los reportes son filtrables por rango de fechas y exportables a PDF y Excel.

### 9.1 Reportes de Ventas

- Ventas totales por día, semana, mes y rango personalizado
- Ventas desglosadas por método de pago
- Ventas por turno y por cajero
- Ticket promedio por mesa y por período
- Comparativa del período actual vs. período anterior (variación porcentual)
- Gráfico de líneas de ventas acumuladas

### 9.2 Reportes de Productos

- Ranking de productos más vendidos por cantidad e ingresos generados
- Productos con menor rotación en el período seleccionado
- Ventas por categoría con gráfico de dona y tabla de porcentajes
- Rentabilidad por producto: precio de venta vs. costo de receta vs. margen neto

### 9.3 Reportes de Servicio

- Desempeño por mesero: comandas atendidas, ventas generadas, ticket promedio, propinas
- Tiempos de cocina promedio por producto y por área
- Tasa de cancelación de ítems con detalle de motivos más frecuentes
- Mesas con mayor rotación y tiempo promedio de ocupación

### 9.4 Reportes de Caja

- Historial completo de cierres de caja con diferencias registradas
- Flujo de caja diario: ingresos, egresos (si aplica) y saldo final
- Descuentos aplicados por período con desglose por tipo

### 9.5 Dashboard Ejecutivo

- KPIs en tiempo real: ventas del día, mesas actualmente ocupadas, comandas en cocina, alertas críticas
- Gráfico de barras de ventas por hora (identificación de horarios pico)
- Mapa de calor: días de la semana vs. ingresos (D3.js heatmap)
- Resumen de inventario crítico en el panel principal
- Widget de últimas ventas y últimos tickets emitidos

---

## 10. Módulo 7 — Usuarios y Roles

Implementado con el paquete **Spatie Laravel Permission**. Soporta roles y permisos granulares a nivel de ruta, middleware y UI (directivas Blade `@can`).

### 10.1 Gestión de Usuarios

- CRUD de usuarios: nombre, email, rol, turno asignado, foto de perfil
- **PIN de 4 dígitos** para acceso rápido al terminal POS en tablets (sin teclado físico)
- Activar / desactivar usuario sin eliminarlo del historial de acciones
- Registro de audit log: cada acción sensible queda guardada con usuario, fecha/hora e IP

### 10.2 Roles y Permisos

- Roles predefinidos: `Administrador`, `Gerente`, `Cajero`, `Mesero`, `Cocinero`
- Permisos granulares disponibles:

| Permiso | Descripción |
|---------|-------------|
| `ver_reportes` | Acceder al módulo de reportes |
| `editar_menu` | Crear y modificar productos y categorías |
| `procesar_pagos` | Cobrar cuentas y cerrar comandas |
| `gestionar_usuarios` | CRUD de usuarios del sistema |
| `abrir_caja` | Abrir y cerrar turnos de caja |
| `cancelar_items` | Cancelar ítems de una comanda abierta |
| `aplicar_descuentos` | Aplicar descuentos durante el cobro |
| `ver_inventario` | Acceder al módulo de inventario |
| `editar_inventario` | Registrar movimientos y compras |
| `configurar_sistema` | Acceder al módulo de configuración |

- Asignación de permisos adicionales a usuarios individuales por encima de su rol base
- Restricción de acceso a módulos completos y a acciones específicas dentro de la UI

### 10.3 Autenticación

- Login estándar con email y contraseña (Laravel Breeze/Fortify)
- Autenticación por PIN de 4 dígitos para el terminal POS (sin requerir teclado)
- Bloqueo automático de sesión por inactividad (tiempo configurable por rol)
- Log out global: cerrar todas las sesiones activas de un usuario desde el admin
- Registro de intentos fallidos de login con bloqueo temporal tras N intentos

---

## 11. Módulo 8 — Configuración

### 11.1 Datos del Restaurante

- Nombre comercial, RUC, dirección, teléfono, correo, logo
- Datos usados en todos los tickets, facturas y reportes
- Configuración SRI: tipo de contribuyente, ambiente (pruebas / producción), certificado de firma electrónica (.p12)
- Moneda (USD para Ecuador), separador decimal, zona horaria y formato de fecha

### 11.2 Mesas y Zonas

- CRUD de zonas: nombre, descripción, capacidad total
- CRUD de mesas por zona: número, capacidad, estado por defecto
- Editor de plano visual: posicionar mesas en un canvas con drag & drop
- Definir forma de mesa (cuadrada, redonda, rectangular) para el plano

### 11.3 Impresoras

- Registro de impresoras: nombre, tipo (térmica ESC/POS / láser), IP, puerto, modelo
- Asignación de impresora por función: ticket cliente, comanda cocina, cierre de caja, etiquetas
- Prueba de impresión directamente desde el panel de configuración
- Soporte para múltiples impresoras de cocina por área (parrilla, bebidas, etc.)

### 11.4 Turnos y Horarios

- Definir turnos de trabajo con nombre y horario de inicio/fin
- Asignación de meseros y cajeros a cada turno
- Horario de menú: activar/desactivar categorías y productos según el turno activo
- Configuración de horarios de apertura del restaurante para control de acceso

### 11.5 Impuestos, Propinas y Descuentos

- Porcentaje de IVA configurable (actualmente 15% en Ecuador)
- Cargo por servicio de mesa: activar/desactivar y definir porcentaje
- Opciones de propina sugerida: definir los porcentajes que aparecen en pantalla de cobro
- Tipos de descuento disponibles: manual, por código, automático por combo

---

## 12. Módulo 9 — Notificaciones

### 12.1 Notificaciones Internas (In-App)

- Centro de notificaciones en el dashboard con campana y badge de conteo
- Tipos de notificación:
  - Stock de ingrediente por debajo del mínimo
  - Comanda lista en cocina esperando al mesero
  - Cierre de caja pendiente al final del turno
  - Nuevo usuario creado en el sistema
  - Error en envío de factura al SRI
- Marcar como leída, marcar todas como leídas, limpiar historial

### 12.2 Notificaciones por Email

- Resumen diario de ventas al gerente/administrador al cierre del día
- Alerta cuando un ingrediente crítico alcanza el stock mínimo
- Notificación de error en certificado electrónico SRI (vencimiento próximo)
- Reporte semanal de desempeño (configurable: activar/desactivar cada tipo)

### 12.3 Notificaciones por WhatsApp (Opcional)

- Integración con **WhatsApp Business API** o **Twilio**
- Envío de ticket digital al cliente si proporcionó su número
- Alertas operativas urgentes al administrador (ej: caja no abierta a la hora de apertura)

---

## 13. Arquitectura Técnica

### 13.1 Stack Tecnológico

| Capa | Tecnología | Versión | Función |
|------|-----------|---------|---------|
| Backend | Laravel | 11.x | API, lógica de negocio, autenticación |
| Frontend | Livewire + Alpine.js | 3.x / 3.x | Componentes reactivos sin SPA separado |
| Base de datos | MySQL | 8.0+ | Almacenamiento relacional principal |
| Cola de trabajos | Laravel Horizon | 5.x | Jobs: impresión, emails, sincronización SRI |
| Tiempo real | Laravel Reverb | 1.x | WebSockets para broadcast del KDS |
| Gráficos | Chart.js + D3.js | 4.5+ / 7.x | Reportes, dashboard, heatmaps |
| Roles | Spatie Permission | 6.x | RBAC granular por rol y usuario |
| Caché / Colas | Redis | 7.x | Sesiones, caché de menú, queue driver |
| Servidor | Nginx + PHP-FPM | PHP 8.3 | Producción |
| Impresión | escpos-php / QZ Tray | — | Impresoras térmicas de red |

### 13.2 Estructura de Carpetas Laravel

```
app/
├── Http/
│   ├── Controllers/         # Controladores REST ligeros
│   └── Middleware/          # Auth, roles, throttle
├── Livewire/
│   ├── POS/                 # Componentes de sala y comandas
│   ├── Kitchen/             # KDS en tiempo real
│   ├── Cashier/             # Caja y cobro
│   └── Reports/             # Gráficos y tablas de reportes
├── Models/                  # Eloquent models con relaciones
├── Services/                # Lógica de negocio desacoplada
│   ├── OrderService.php
│   ├── PaymentService.php
│   ├── InventoryService.php
│   └── SriService.php       # Factura electrónica Ecuador
├── Events/                  # OrderSent, ItemUpdated, etc.
├── Listeners/               # Oyentes de eventos
├── Jobs/                    # PrintTicket, SendEmail, SyncSri
└── Policies/                # Autorización por modelo

database/
├── migrations/
└── seeders/

resources/views/
├── livewire/
│   ├── pos/
│   ├── kitchen/
│   └── reports/
└── layouts/
```

### 13.3 Patrones de diseño aplicados

- **Service Layer:** La lógica de negocio compleja (OrderService, PaymentService) se desacopla de los controladores y componentes Livewire
- **Repository Pattern (opcional):** Para abstraer las consultas complejas de reportes
- **Event / Listener:** Para desacoplar acciones como "comanda enviada a cocina" del broadcast y del descuento de inventario
- **Job Queue:** Toda tarea costosa (PDF, email, SRI) se procesa en background con Laravel Horizon

---

## 14. Esquema de Base de Datos

### Tablas principales

| Tabla | Descripción | Relaciones clave |
|-------|-------------|-----------------|
| `users` | Usuarios con rol, PIN y estado | `roles` (Spatie) |
| `zones` | Zonas del restaurante | `tables` |
| `tables` | Mesas con estado y zona | `orders`, `zones` |
| `orders` | Comandas: mesa, mesero, estado, timestamps | `tables`, `users`, `order_items`, `payments` |
| `order_items` | Ítems de cada comanda con estado cocina | `orders`, `products` |
| `order_item_modifiers` | Modificadores seleccionados por ítem | `order_items`, `modifier_options` |
| `products` | Productos del menú | `categories`, `modifiers`, `recipes` |
| `categories` | Categorías y sub-categorías | `products` |
| `modifier_groups` | Grupos de modificadores (Tamaño, Extras…) | `products`, `modifier_options` |
| `modifier_options` | Opciones dentro de cada grupo | `modifier_groups` |
| `payments` | Pagos procesados por método | `orders`, `cash_registers` |
| `cash_registers` | Turnos de caja con apertura y cierre | `users`, `payments` |
| `ingredients` | Insumos con stock y costo | `recipes`, `inventory_movements` |
| `recipes` | Producto ↔ ingrediente con cantidad | `products`, `ingredients` |
| `inventory_movements` | Historial de entradas/salidas de stock | `ingredients`, `users` |
| `suppliers` | Proveedores con datos de contacto | `purchase_orders` |
| `purchase_orders` | Órdenes de compra a proveedores | `suppliers`, `purchase_order_items` |
| `notifications` | Notificaciones internas por usuario | `users` |
| `audit_logs` | Registro de acciones sensibles | `users` |

### Campos clave — tabla `orders`

```sql
CREATE TABLE orders (
  id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  table_id       BIGINT UNSIGNED NULL,           -- NULL si es delivery/takeaway
  user_id        BIGINT UNSIGNED NOT NULL,        -- mesero que abrió la comanda
  cashier_id     BIGINT UNSIGNED NULL,            -- cajero que procesó el pago
  type           ENUM('dine_in','takeaway','delivery') DEFAULT 'dine_in',
  status         ENUM('open','sent','partial','complete','cancelled') DEFAULT 'open',
  notes          TEXT NULL,
  subtotal       DECIMAL(10,2) DEFAULT 0,
  discount       DECIMAL(10,2) DEFAULT 0,
  tax            DECIMAL(10,2) DEFAULT 0,
  tip            DECIMAL(10,2) DEFAULT 0,
  total          DECIMAL(10,2) DEFAULT 0,
  opened_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  closed_at      TIMESTAMP NULL,
  deleted_at     TIMESTAMP NULL,                  -- soft delete
  created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Campos clave — tabla `order_items`

```sql
CREATE TABLE order_items (
  id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id       BIGINT UNSIGNED NOT NULL,
  product_id     BIGINT UNSIGNED NOT NULL,
  quantity       TINYINT UNSIGNED NOT NULL DEFAULT 1,
  unit_price     DECIMAL(10,2) NOT NULL,
  subtotal       DECIMAL(10,2) NOT NULL,
  notes          VARCHAR(255) NULL,
  kitchen_status ENUM('pending','preparing','ready','cancelled') DEFAULT 'pending',
  sent_at        TIMESTAMP NULL,
  ready_at       TIMESTAMP NULL,
  cancelled_at   TIMESTAMP NULL,
  cancelled_by   BIGINT UNSIGNED NULL,
  cancel_reason  VARCHAR(255) NULL,
  deleted_at     TIMESTAMP NULL,
  created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

---

## 15. Flujo de Tiempo Real (KDS)

La comunicación en tiempo real usa **Laravel Reverb** (servidor WebSocket nativo de Laravel) con el cliente **Laravel Echo** en el frontend Livewire.

### Eventos de broadcast

| Evento | Canal | Disparado cuando |
|--------|-------|-----------------|
| `OrderSent` | `kitchen.{area}` | Mesero envía ítems a cocina |
| `ItemStatusUpdated` | `kitchen.{area}` | Cocinero cambia estado de un ítem |
| `OrderCompleted` | `table.{table_id}` | Toda la comanda está lista |
| `TableStatusChanged` | `pos.tables` | Una mesa cambia de estado |
| `StockAlert` | `admin` | Ingrediente baja del mínimo |
| `CashRegisterAlert` | `admin` | Caja no abierta a la hora configurada |

### Configuración de Echo en el componente KDS

```javascript
// resources/js/kitchen.js
Echo.channel('kitchen.hot')
  .listen('OrderSent', (e) => {
    // Livewire.dispatch para actualizar el componente
    Livewire.dispatch('order-received', { order: e.order });
    playAlert();
  })
  .listen('ItemStatusUpdated', (e) => {
    Livewire.dispatch('item-updated', { item: e.item });
  });
```

### Alternativa sin WebSockets (Polling)

Para entornos sin soporte de WebSockets, el componente KDS puede usar `wire:poll`:

```php
// En el componente Livewire KDS
#[On('order-received')]
public function mount(): void
{
    $this->loadOrders();
}

// En la vista Blade
<div wire:poll.5s="loadOrders">
    @foreach($orders as $order)
        <x-kitchen-card :order="$order" />
    @endforeach
</div>
```

---

## 16. Plan de Desarrollo por Fases

Se recomienda desarrollo iterativo en 4 fases, entregando valor funcional desde la primera semana.

| Fase | Duración estimada | Módulos incluidos | Entregable |
|------|-------------------|-------------------|-----------|
| **Fase 1 — MVP operativo** | 3 semanas | Auth + Roles, Mesas, Menú, Comandas, KDS básico | Sistema funcionando en sala y cocina |
| **Fase 2 — Caja completa** | 2 semanas | Caja, Pagos, Tickets, Factura SRI Ecuador | Operación comercial completa |
| **Fase 3 — Control y gestión** | 3 semanas | Inventario + Recetas, Reportes, Notificaciones | Gestión gerencial y analítica |
| **Fase 4 — Optimización** | 2 semanas | Config avanzada, Reverb WebSockets, PWA, Delivery | Escalabilidad y experiencia premium |

### Prioridad dentro de la Fase 1

1. Migraciones y seeders base (usuarios, mesas, productos de prueba)
2. Auth con roles Spatie y PIN para POS
3. CRUD de menú (categorías + productos)
4. Plano de mesas con Livewire
5. Componente de comanda (agregar ítems, enviar a cocina)
6. KDS con polling de 5 segundos
7. Cobro básico en efectivo

---

## 17. Seguridad y Rendimiento

### 17.1 Seguridad

- Todas las rutas protegidas con middleware `auth` y `can` (Spatie)
- Tokens CSRF en todos los formularios Livewire (automático)
- Rate limiting en login (5 intentos, bloqueo 15 min) y en endpoints sensibles
- **Soft deletes en todo:** órdenes, pagos, productos, usuarios — nunca eliminación física
- Audit log de acciones sensibles: pagos, cancelaciones, descuentos, cambios de configuración
- Tokens Sanctum para cualquier integración con apps móviles o APIs externas
- Validación y sanitización estricta en todos los formularios del lado del servidor
- Certificado SSL/TLS obligatorio en producción

### 17.2 Rendimiento

- Caché de menú completo en Redis (invalidar automáticamente al guardar cambios)
- Eager loading en consultas de comandas: `with('items.product.category', 'table', 'user')`
- Paginación en listas de reportes (máximo 100 registros por carga)
- Jobs en queue para tareas costosas: generación de PDF, envío de email, factura SRI
- Índices de base de datos en columnas críticas:
  - `order_items.kitchen_status`
  - `orders.table_id, orders.status, orders.created_at`
  - `products.category_id, products.is_active`
  - `inventory_movements.ingredient_id, inventory_movements.created_at`
- Horizon para monitoreo de colas en tiempo real

---

## 18. Compatibilidad y Dispositivos

| Dispositivo | Uso principal | Módulos | Notas |
|-------------|--------------|---------|-------|
| Tablet (iPad / Android) | Mesero tomando comandas | POS, Mesas | Pantalla táctil, modo horizontal |
| Monitor / TV grande | KDS en cocina | Cocina | Modo oscuro, fuente grande, solo toque básico |
| Desktop / laptop | Administración | Admin, Reportes, Config | Pantalla completa, mouse + teclado |
| Laptop / tablet | Cajero | Caja, POS | Puede ser el mismo dispositivo del mesero |
| Impresora térmica | Tickets y comandas | Todos | Conexión por red (IP), protocolo ESC/POS |

- **Responsive:** diseño adaptado para los tres breakpoints principales (mobile 390px, tablet 768px, desktop 1280px)
- **Navegadores compatibles:** Chrome 120+, Edge 120+, Safari 17+, Firefox 121+
- **PWA opcional:** `manifest.json` + service worker para instalar como app en tablets sin App Store ni Play Store
- **Modo offline parcial:** el menú y el plano de mesas pueden cachearse localmente; las comandas requieren conexión para sincronizarse con la cocina

---

---

## 13. Módulo 10 — WhatsApp Marketing & Chatbot

Extensión del sistema POS para gestionar comunicación con clientes a través de **WhatsApp Business API oficial (Meta)**. Cubre tres pilares: campañas de marketing con multimedia, catálogo digital sincronizado con el menú del POS, y chatbot de respuestas automáticas con handoff a agente humano.

> **Stack adicional:** Meta Cloud API v19.0 · BotMan / flujo propio Laravel · AWS S3 (media) · Laravel Horizon (queue de envíos)

---

### 13.1 Arquitectura de Integración con Meta WABA

#### Componentes del sistema

| Componente | Tecnología / Proveedor | Función |
|-----------|----------------------|---------|
| Meta Cloud API | graph.facebook.com/v19.0 | Envío de mensajes, plantillas, catálogo |
| Webhook Receptor | Laravel Route + Controller | Recibe eventos entrantes de Meta |
| Queue Driver | Laravel Horizon + Redis | Procesa envíos masivos sin timeout HTTP |
| Media Storage | AWS S3 / DigitalOcean Spaces | Almacena imágenes de campañas y catálogo |
| Chatbot Engine | Laravel + BotMan o flujo propio | Lógica de respuestas automáticas |
| Base de datos | MySQL 8 (tablas nuevas) | Contactos, campañas, conversaciones, logs |

#### Flujo general de un mensaje saliente

| Paso | Actor | Acción |
|------|-------|--------|
| 1 | Gerente | Crea campaña en el panel del POS, selecciona plantilla y audiencia |
| 2 | Sistema | Valida la plantilla aprobada por Meta y encola el Job de envío |
| 3 | Laravel Horizon | Procesa el Job: llama a Meta Cloud API por cada contacto (rate: 80 msg/seg) |
| 4 | Meta | Entrega el mensaje al destinatario en WhatsApp |
| 5 | Cliente | Responde al mensaje (texto, botón o reacción) |
| 6 | Meta | Envía evento al Webhook del restaurante vía HTTPS POST |
| 7 | Chatbot | Evalúa la respuesta y activa el flujo correspondiente |
| 8 | Sistema | Registra la interacción en la base de datos y actualiza métricas |

#### Credenciales y configuración requerida

- Cuenta Meta Business verificada con nombre del restaurante
- Número de teléfono **dedicado** registrado en WABA (no puede ser un número activo en WhatsApp personal)
- App de Facebook con permisos: `whatsapp_business_messaging`, `whatsapp_business_management`
- Token de acceso permanente (System User Token) con permisos de envío
- Webhook URL pública con HTTPS y verificación de firma HMAC-SHA256
- Cuenta Business Manager aprobada para plantillas HSM (Highly Structured Messages)

---

### 13.2 Roles y Permisos del Módulo

| Rol | Campañas | Catálogo | Chatbot | Contactos | Reportes |
|-----|----------|----------|---------|-----------|----------|
| **Administrador** | Total | Total | Total | Total | Total |
| **Gerente** | Crear / Enviar | Editar | Configurar flujos | Ver / Exportar | Total |
| **Cajero** | Solo ver | Solo ver | Sin acceso | Sin acceso | Básico |
| **Mesero** | Sin acceso | Sin acceso | Sin acceso | Sin acceso | Sin acceso |
| **Cocinero** | Sin acceso | Sin acceso | Sin acceso | Sin acceso | Sin acceso |

---

### 13.3 Campañas de Marketing

#### Tipos de plantilla HSM soportados

Meta exige que los mensajes iniciados por la empresa usen plantillas pre-aprobadas. El panel permite crearlas, enviarlas a revisión y gestionar su estado.

| Tipo de plantilla | Contenido permitido | Caso de uso |
|------------------|--------------------|-----------  |
| `TEXT` | Solo texto con variables `{{1}}`, `{{2}}` | Recordatorios, confirmaciones |
| `IMAGE + TEXT` | Imagen encabezado + texto + botones | Promociones de platos, descuentos |
| `VIDEO + TEXT` | Video encabezado + texto | Lanzamiento de nuevos platillos |
| `DOCUMENT` | PDF adjunto + texto | Menú completo en PDF, carta de vinos |
| `CAROUSEL` | Hasta 10 tarjetas con imagen + botón | Vitrina de postres, combos del día |
| `CATALOG` | Productos del catálogo WABA | Ver menú completo desde WhatsApp |

#### Funcionalidades del gestor de campañas

- Crear campaña: nombre, plantilla, audiencia (todos / segmento / lista manual)
- Editor visual de plantilla con preview en tiempo real del aspecto en WhatsApp
- Carga de imagen o video directamente desde el panel — se sube a S3 y se referencia por URL
- Programar envío: inmediato, en fecha/hora específica, o recurrente (ej: cada viernes a las 12:00)
- Variables dinámicas por contacto: `{{nombre}}`, `{{plato_del_dia}}`, `{{descuento}}`
- Botones de acción (hasta 3): respuesta rápida, enlace a URL o llamada telefónica
- Vista previa antes de enviar con muestra de los primeros 3 destinatarios
- Límites de envío configurables por día para no superar el tier de la cuenta Meta

#### Casos de uso de campañas

| Campaña | Frecuencia sugerida | Plantilla | Segmento |
|---------|--------------------|-----------|---------  |
| Postre del día | Diaria 11:00 AM | IMAGE + TEXT + botón "Ver más" | Todos los contactos activos |
| Menú del fin de semana | Viernes 12:00 PM | CAROUSEL con 5 platos | Contactos con pedidos previos |
| Promoción especial | Ad hoc | IMAGE + TEXT + botón "Pedir ahora" | Segmento personalizado |
| Combo feliz hora | Diaria 17:00 | TEXT con variables de precio | Clientes recurrentes |
| Catálogo completo | Semanal | CATALOG message | Nuevos contactos |
| Recordatorio de reserva | Automático D-1 | TEXT + nombre del cliente | Con reserva confirmada |

---

### 13.4 Catálogo Digital de WhatsApp

WhatsApp Business permite publicar un catálogo nativo que los clientes pueden explorar directamente desde la app, sin salir de la conversación. Este módulo sincroniza el catálogo del POS con el catálogo de Meta.

#### Estructura del catálogo

- Cada producto del POS puede tener un registro espejo en el catálogo de WhatsApp
- Campos sincronizados: nombre, descripción, precio, imagen principal, categoría, SKU, disponibilidad
- Los productos desactivados en el POS se marcan como `out of stock` en el catálogo automáticamente
- Sincronización manual (botón "Sincronizar catálogo") o automática al guardar cambios en el menú

#### Sincronización POS ↔ Meta Catalog

| Evento en POS | Acción en catálogo Meta |
|--------------|------------------------|
| Producto activado | Se publica en el catálogo (`status: active`) |
| Producto desactivado | Se marca como agotado (`availability: out of stock`) |
| Precio modificado | Se actualiza el precio en Meta via `PATCH` |
| Foto del producto cambiada | Se sube nueva imagen a Meta y se actualiza el `item_id` |
| Producto eliminado (soft delete) | Se elimina del catálogo de Meta |

#### Envío de catálogo en conversación

- El chatbot puede enviar el catálogo completo o por categoría cuando el cliente lo solicita
- El gerente puede enviar manualmente el catálogo desde la vista de conversación
- Soporta *catalog message* nativo de WABA: el cliente ve los productos con imagen, precio y botón "Ver"

---

### 13.5 Chatbot de Respuestas Automáticas

El chatbot funciona con un motor de flujos basado en estados (máquina de estados finitos), implementado en Laravel. Para la versión inicial se usan flujos estructurados con palabras clave y botones de respuesta rápida. En una segunda fase se puede integrar la API de Claude para respuestas en lenguaje natural.

#### Flujo 1 — Bienvenida

| Trigger | Respuesta del bot | Opciones |
|---------|------------------|----------|
| hola / buenos días / hi | ¡Bienvenido a [Restaurante]! 🍽️ ¿En qué te podemos ayudar? | 1. Ver menú · 2. Hacer un pedido · 3. Promociones · 4. Hablar con alguien |
| 1 / "Ver menú" | Aquí tienes nuestro menú de hoy 👇 [catalog message] | Ver categoría / Volver |
| 2 / "Hacer un pedido" | ¿Tu pedido es para llevar o delivery? | Para llevar / Delivery |
| 3 / "Promociones" | Estas son nuestras promociones de hoy 🔥 [imagen de campaña] | Quiero este / Ver más / Volver |
| 4 / "Hablar con alguien" | Te estamos conectando con un asesor. Tiempo estimado: 2 min ⏳ | Handoff a agente humano |

#### Flujo 2 — Consulta de menú

- El cliente pregunta por producto o categoría: "¿Tienen ensaladas?", "¿Cuánto cuesta el lomo?", "Quiero ver los postres"
- El bot busca en la BD del POS por nombre y responde con disponibilidad y precio
- Si el producto está disponible: muestra imagen, nombre, precio y botón "Agregar al pedido"
- Si está agotado: informa e invita a ver alternativas de la misma categoría
- Si no lo encuentra: envía el catálogo completo y ofrece asistencia humana

#### Flujo 3 — Toma de pedido

| Paso | Bot pregunta | Cliente responde |
|------|-------------|-----------------|
| 1 | ¿Tu pedido es para llevar o delivery? | Para llevar / Delivery |
| 2 | ¿Cuál es tu nombre? | Texto libre |
| 3 | ¿Qué deseas ordenar? Puedes enviarme los productos o elegir del catálogo. | Texto libre o selección del catálogo |
| 4 (delivery) | ¿Cuál es tu dirección de entrega? | Texto libre o ubicación compartida |
| 5 | Resumen del pedido: [lista]. ¿Confirmamos? | Confirmar / Modificar |
| 6 | ¡Pedido recibido! 🎉 Número de orden: #{{orden_id}}. Tiempo estimado: {{tiempo}} min. | — |
| 7 (opcional) | ¿Cómo prefieres pagar? Efectivo / Transferencia / Tarjeta en entrega | Selección de método |

> Al confirmar el pedido, el sistema crea automáticamente una orden en el POS con tipo `takeaway` o `delivery`, asignada al turno activo.

#### Flujo 4 — Respuesta a promociones

- El cliente hace clic en el botón "Quiero este descuento" de una campaña
- El bot registra el interés, responde con instrucciones de canje y lo marca en el CRM interno
- El gerente puede ver en el panel qué clientes respondieron a cada campaña
- Si el cliente responde "no gracias", el bot agradece y cierra el flujo sin spam

#### Handoff a agente humano

- El cliente puede escribir `agente`, `persona`, `ayuda` en cualquier momento para pasar a atención humana
- El chatbot pausa automáticamente y notifica al gerente en el panel del POS
- El gerente responde desde el panel de conversaciones del POS (sin usar el teléfono)
- Tras resolver la consulta, el gerente puede reactivar el bot o cerrar la conversación
- Si el cliente no responde en 24 horas, la conversación se cierra automáticamente

#### Palabras clave configurables

| Palabra clave / frase | Acción del bot |
|----------------------|----------------|
| hola, buenos días, hi, buenas | Inicia flujo de bienvenida |
| menú, carta, qué tienen, qué hay | Envía catálogo por categoría |
| precio de {{producto}} | Busca en BD y responde con precio actual |
| pedido, ordenar, quiero pedir | Inicia flujo de toma de pedido |
| promociones, ofertas, descuentos | Envía campañas activas del día |
| postres, dulces | Muestra categoría Postres del catálogo |
| horario, hora, cuándo abren | Responde con horario configurado |
| teléfono, dirección, ubicación | Responde con datos del restaurante + link Google Maps |
| gracias, listo, ok, bye | Despedida y cierre de sesión activa del bot |
| agente, persona, humano, ayuda | Pausa bot y notifica al gerente |

---

### 13.6 Gestión de Contactos (Mini-CRM)

#### Perfil de contacto

- Nombre (obtenido del perfil de WhatsApp o capturado en el flujo de pedido)
- Número de teléfono (identificador único)
- Etiquetas: cliente frecuente, nuevo cliente, interesado en postres, delivery, etc.
- Historial de pedidos realizados por WhatsApp con montos y fechas
- Campañas recibidas y tasa de apertura / respuesta
- Estado de suscripción: activo, bloqueado, no molestar (opt-out)

#### Segmentación de audiencia

| Segmento | Criterio | Ejemplo de uso |
|---------|---------|---------------|
| Todos los contactos | Sin filtro | Lanzamiento de nuevo menú |
| Clientes frecuentes | 3+ pedidos en los últimos 30 días | Programa de fidelización |
| Amantes de postres | Etiqueta "postre" o compra en categoría | Campaña especial de postres |
| Delivery activo | Etiqueta "delivery" o pedido tipo delivery | Promoción de envío gratis |
| Sin pedidos en 30 días | Última compra > 30 días | Campaña de reactivación |
| Respondieron campaña X | Registro de interacción con campaña | Seguimiento de conversión |

#### Opt-out y cumplimiento legal

- Opción de opt-out incluida en cada campaña (`Responde STOP para no recibir más mensajes`)
- Al recibir `STOP`, el contacto se excluye automáticamente de todas las campañas futuras
- Registro de consentimiento con timestamp para trazabilidad legal

---

### 13.7 Esquema de Base de Datos — Tablas Nuevas

| Tabla | Descripción | Relaciones clave |
|-------|------------|-----------------|
| `whatsapp_contacts` | Contactos: número, nombre, opt-out, etiquetas, última interacción | `whatsapp_conversations`, `whatsapp_campaign_logs` |
| `whatsapp_conversations` | Historial de mensajes por contacto con dirección (in/out) y estado | `whatsapp_contacts`, `users` (agente) |
| `whatsapp_campaigns` | Campañas: nombre, plantilla, audiencia, programación, estado | `whatsapp_campaign_logs` |
| `whatsapp_campaign_logs` | Log de envío por contacto: estado (sent, delivered, read, failed) | `whatsapp_campaigns`, `whatsapp_contacts` |
| `whatsapp_templates` | Plantillas HSM con estado de aprobación Meta | `whatsapp_campaigns` |
| `whatsapp_chatbot_flows` | Flujos del chatbot: trigger, respuesta, tipo, opciones en JSON | — |
| `whatsapp_catalog_items` | Espejo del catálogo Meta: `item_id` de Meta + `product_id` del POS | `products` |
| `whatsapp_orders` | Pedidos creados desde WhatsApp vinculados al POS | `orders`, `whatsapp_contacts` |

---

### 13.8 Reportes y Métricas

#### Métricas de campañas

| Métrica | Definición |
|---------|-----------|
| Mensajes enviados | Total de mensajes enviados en la campaña |
| Tasa de entrega | Mensajes entregados / enviados |
| Tasa de lectura | Mensajes leídos (doble check azul) / entregados |
| Tasa de respuesta | Contactos que respondieron / mensajes leídos |
| Tasa de conversión | Pedidos generados / mensajes enviados |
| Opt-outs generados | Contactos que respondieron STOP (alerta si > 1%) |
| Ingresos atribuidos | Suma de pedidos originados en la campaña (USD) |

#### Métricas del chatbot

- Conversaciones iniciadas por día / semana / mes
- Flujos más utilizados (ranking de triggers activados)
- Tasa de resolución: conversaciones cerradas por el bot sin intervención humana
- Tasa de handoff: porcentaje que requirió agente humano
- Pedidos completados por WhatsApp vs. pedidos totales del restaurante
- Tiempo promedio de respuesta del agente en handoffs

---

### 13.9 Plan de Desarrollo — Fases WhatsApp

| Fase | Duración | Contenido | Prerequisito |
|------|---------|-----------|-------------|
| **WA-1: Fundación** | 2 semanas | Registro WABA, webhook, tablas BD, envío manual de mensajes, plantilla de bienvenida | Meta Business verificado, número dedicado |
| **WA-2: Campañas** | 2 semanas | Gestor de campañas, plantillas IMAGE/TEXT, segmentación básica, reportes de entrega | Fase WA-1 completada |
| **WA-3: Catálogo** | 1 semana | Sincronización POS ↔ Meta, envío de catálogo desde conversación | Módulo Menú del POS estable |
| **WA-4: Chatbot básico** | 2 semanas | Flujos de bienvenida, consulta de menú, handoff a agente, editor sin código | Fase WA-2 completada |
| **WA-5: Pedidos WA** | 2 semanas | Flujo de toma de pedido completo, integración con POS (crea orden automáticamente) | Módulo Caja del POS estable |
| **WA-6: IA opcional** | 2 semanas | Integración Claude API para respuestas en lenguaje natural fuera de flujos estructurados | Fase WA-4 completada |

---

### 13.10 Seguridad y Limitaciones Operativas

#### Seguridad de la integración

- Verificación de firma HMAC-SHA256 en cada webhook recibido de Meta (obligatorio)
- Token de acceso almacenado en variables de entorno `.env`, nunca en código fuente ni BD
- Rotación del System User Token recomendada cada 90 días
- Rate limiting en el endpoint del webhook para prevenir abuso
- Logs completos de todos los mensajes enviados y recibidos para auditoría

#### Limitaciones operativas de Meta WABA

| Limitación | Detalle | Mitigación |
|-----------|---------|-----------|
| Ventana de 24 horas | Solo se puede responder libremente dentro de 24h de la última respuesta del cliente | Usar plantillas HSM para mensajes fuera de la ventana |
| Tier de mensajes | Nuevo número: 1.000 conversaciones/día. Aumenta con volumen y calificación | Calentar el número gradualmente en las primeras semanas |
| Aprobación de plantillas | Meta puede tardar 24-48h en aprobar una plantilla nueva | Preparar plantillas con anticipación antes de campañas importantes |
| Política anti-spam | Muchos opt-outs o reportes bajan la calificación del número | Respetar opt-outs, no enviar campañas más de 2-3 veces por semana |
| Costo por conversación | Meta cobra por conversación de 24h iniciada por empresa (aprox. USD 0.04-0.08 en Ecuador) | Incluir en el presupuesto operativo del módulo |

---

*Sistema POS Web — Restaurante · Documento Técnico v1.0 · Junio 2026*  
*Confidencial — Uso interno*
