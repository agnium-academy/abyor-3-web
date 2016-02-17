using System;
using System.Collections.Generic;
using System.Data;
using System.Data.OleDb;
using System.Data.SqlClient;
using System.IO;
using System.Linq;
using System.Text;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace Abyor3
{
    public partial class DataSiswa : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            this.tampilData();

        }
        
        private void tampilData()
        {
            AdministrasiDBEntities db = new AdministrasiDBEntities();
            
            var query = from siswa in db.Siswa
                        orderby siswa.nama
                        select siswa;
            this.grdBadanUsaha.DataSource = query.ToList();
            this.grdBadanUsaha.DataBind();
        }

        protected void btnSave_Click(object sender, EventArgs e)
        {

        }
    }
}