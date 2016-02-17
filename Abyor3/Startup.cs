using Microsoft.Owin;
using Owin;

[assembly: OwinStartupAttribute(typeof(Abyor3.Startup))]
namespace Abyor3
{
    public partial class Startup {
        public void Configuration(IAppBuilder app) {
            ConfigureAuth(app);
        }
    }
}
