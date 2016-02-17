<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="DataSiswa.aspx.cs" Inherits="Abyor3.DataSiswa" %>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
</head>
<body>
    <script src="Scripts/jquery-1.10.2.js"></script>
    <script src="Scripts/bootstrap.js"></script>
    <script src="Content/bootstrap.css"></script>
    <form id="form1" runat="server">
    <div>
    Daftar Siswa SMA N 1 Abyor
        <asp:GridView CssClass="EU_DataTable" AllowCustomPaging="false" runat="server" ID="grdBadanUsaha" AllowPaging="false" CellPadding="3" CellSpacing="2" EmptyDataText="Tidak ada data" ShowHeaderWhenEmpty="True" Width="90%" AutoGenerateColumns="false">
            <Columns>
                <asp:TemplateField HeaderText="No." HeaderStyle-ForeColor="White">
                        <ItemTemplate>
                            <%# Container.DataItemIndex + 1 %>
                        </ItemTemplate>
                    </asp:TemplateField>
                <asp:BoundField DataField="nis" HeaderText="NIS">
                        <HeaderStyle ForeColor="White" />
                    </asp:BoundField>
                    <asp:BoundField DataField="nama" HeaderText="NAMA">
                        <HeaderStyle ForeColor="White" />
                    </asp:BoundField>
                <asp:BoundField DataField="tempatLahir" HeaderText="NIS">
                        <HeaderStyle ForeColor="White" />
                    </asp:BoundField>
                    <asp:BoundField DataField="alamat" HeaderText="Nama">
                        <HeaderStyle ForeColor="White" />
                    </asp:BoundField>
                <asp:BoundField DataField="tanggalLahir" HeaderText="Tanggal Lahir">
                        <HeaderStyle ForeColor="White" />
                    </asp:BoundField>
                    <asp:BoundField DataField="alamat" HeaderText="Alamat">
                        <HeaderStyle ForeColor="White" />
                    </asp:BoundField>
                <asp:BoundField DataField="agama" HeaderText="Agama">
                        <HeaderStyle ForeColor="White" />
                    </asp:BoundField>
                    <asp:BoundField DataField="noHp" HeaderText="No HP">
                        <HeaderStyle ForeColor="White" />
                    </asp:BoundField>
                <asp:BoundField DataField="email" HeaderText="Email">
                        <HeaderStyle ForeColor="White" />
                    </asp:BoundField>
            </Columns>        
            <AlternatingRowStyle BackColor="White" ForeColor="#284775" />
                    <EditRowStyle BackColor="#999999" />
                    <FooterStyle BackColor="#5D7B9D" Font-Bold="True" ForeColor="White" />
                    <HeaderStyle BackColor="#5D7B9D" Font-Bold="True" ForeColor="White" />
                    <PagerStyle BackColor="#284775" ForeColor="White" HorizontalAlign="Center" />
                    <RowStyle BackColor="#F7F6F3" ForeColor="#333333" />
                    <SelectedRowStyle BackColor="#E2DED6" Font-Bold="True" ForeColor="#333333" />
                    <SortedAscendingCellStyle BackColor="#E9E7E2" />
                    <SortedAscendingHeaderStyle BackColor="#506C8C" />
                    <SortedDescendingCellStyle BackColor="#FFFDF8" />
                    <SortedDescendingHeaderStyle BackColor="#6F8DAE" />
                </asp:GridView>
    </div>
        <div style="margin: 10px">
            <asp:Button Text="Tambah" CssClass="btn btn-primary" runat="server" ID="btnSave" OnClick="btnSave_Click"/>
        </div>
    </form>
</body>
</html>
