//------------------------------------------------------------------------------
// <auto-generated>
//     This code was generated from a template.
//
//     Manual changes to this file may cause unexpected behavior in your application.
//     Manual changes to this file will be overwritten if the code is regenerated.
// </auto-generated>
//------------------------------------------------------------------------------

namespace Abyor3
{
    using System;
    using System.Collections.Generic;
    
    public partial class Guru
    {
        [System.Diagnostics.CodeAnalysis.SuppressMessage("Microsoft.Usage", "CA2214:DoNotCallOverridableMethodsInConstructors")]
        public Guru()
        {
            this.Nilai = new HashSet<Nilai>();
        }
    
        public string nip { get; set; }
        public string nama { get; set; }
        public string tempatLahir { get; set; }
        public System.DateTime tanggalLahir { get; set; }
        public string alamat { get; set; }
        public string agama { get; set; }
        public string noHp { get; set; }
        public string email { get; set; }
    
        [System.Diagnostics.CodeAnalysis.SuppressMessage("Microsoft.Usage", "CA2227:CollectionPropertiesShouldBeReadOnly")]
        public virtual ICollection<Nilai> Nilai { get; set; }
    }
}
