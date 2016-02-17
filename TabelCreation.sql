USE [AdministrasiDB]

CREATE TABLE [dbo].[Siswa](
	[nis] [char](8) PRIMARY KEY NOT NULL,
	[nama] [varchar](50) NOT NULL,
	[tempatLahir] [varchar](20) NOT NULL,
	[tanggalLahir] [date] NOT NULL,
	[alamat] [varchar](100) NOT NULL,
	[agama] [varchar](20) NOT NULL,
	[noHp] [varchar](15) NOT NULL,
	[email] [varchar](50) NOT NULL
)

CREATE TABLE [dbo].[Guru](
	[nip] [char](10) PRIMARY KEY NOT NULL,
	[nama] [varchar](50) NOT NULL,
	[tempatLahir] [varchar](20) NOT NULL,
	[tanggalLahir] [date] NOT NULL,
	[alamat] [varchar](100) NOT NULL,
	[agama] [varchar](20) NOT NULL,
	[noHp] [varchar](15) NOT NULL,
	[email] [varchar](50) NOT NULL
)

CREATE TABLE [dbo].[User](
	[username] [varchar](20) PRIMARY KEY NOT NULL,
	[password] [varchar](50) NOT NULL,
	[nama] [varchar](50) NOT NULL,
	[email] [varchar](20) NOT NULL
)

CREATE TABLE [dbo].[Mapel](
	[kodeMapel] [char] (4) PRIMARY KEY NOT NULL,
	[namaMapel] [varchar](50) NOT NULL
)

CREATE TABLE [dbo].[Nilai](
	[id] [int] IDENTITY(1,1) PRIMARY KEY NOT NULL,
	[kodeMapel] [char](4) NOT NULL,
	[nis] [char](8) NOT NULL,
	[nip] [char](10) NOT NULL,
	[nilai] [int] NOT NULL
)

GO
ALTER TABLE [dbo].[Nilai]  ADD  CONSTRAINT [FK_Nilai_Mapel] FOREIGN KEY([kodeMapel])
REFERENCES [dbo].[Mapel] ([kodeMapel])

GO
ALTER TABLE [dbo].[Nilai]  ADD  CONSTRAINT [FK_Nilai_Siswa] FOREIGN KEY([nis])
REFERENCES [dbo].[Siswa] ([nis])

GO
ALTER TABLE [dbo].[Nilai]  ADD  CONSTRAINT [FK_Nilai_Guru] FOREIGN KEY([nip])
REFERENCES [dbo].[Guru] ([nip])
