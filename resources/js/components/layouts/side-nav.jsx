import { useState, useEffect } from 'react';
import { NavLink, Outlet, useLocation } from 'react-router-dom';
import { Dialog, DialogBackdrop, DialogPanel } from '@headlessui/react';

const navigation = [
  { name: 'Customers', href: '/customers' },
  { name: 'Generate Reports', href: '/generate-reports' },
];

export default function SideNavLayout() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const location = useLocation();
  const currentNav = navigation.find((item) => item.href === location.pathname);
  const pageTitle = currentNav ? currentNav.name : 'Invoice Report System';

  // Close mobile drawer automatically if resized to desktop (lg: 1024px)
  useEffect(() => {
    const handleResize = () => {
      if (window.innerWidth >= 1024) {
        setMobileMenuOpen(false);
      }
    };
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  const navLinkClass = ({ isActive }) =>
    `flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition ${
      isActive
        ? 'bg-gray-200 text-black-700'
        : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900'
    }`;

  return (
    <div className="min-h-screen bg-gray-100 flex overflow-x-hidden">
      {/* Mobile Sidebar Drawer */}
      <Dialog
        open={mobileMenuOpen}
        onClose={setMobileMenuOpen}
        className="relative z-50 lg:hidden"
      >
        <DialogBackdrop className="fixed inset-0 bg-gray-900/50 transition-opacity" />

        <div className="fixed inset-0 flex">
          <DialogPanel className="relative mr-16 flex w-full max-w-xs flex-1 bg-white p-6 shadow-xl">
            <div className="flex w-full flex-col justify-between">
              <div>
                {/* Brand & Close Button */}
                <div className="flex items-center justify-between mb-6">
                  <span className="text-lg font-bold text-gray-900">Invoice Report System</span>
                  <button
                    type="button"
                    onClick={() => setMobileMenuOpen(false)}
                    className="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100"
                  >
                    <span className="sr-only">Close sidebar</span>
                    <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>

                {/* Mobile Nav Links */}
                <nav className="space-y-1">
                  {navigation.map((item) => (
                    <NavLink
                      key={item.name}
                      to={item.href}
                      onClick={() => setMobileMenuOpen(false)}
                      className={navLinkClass}
                    >
                      {item.name}
                    </NavLink>
                  ))}
                </nav>
              </div>

              {/* Mobile Bottom User / Logout Area 
              <div className="border-t border-gray-200 pt-4">
                <div className="flex items-center justify-between">
                  <div className="text-xs">
                    <p className="font-semibold text-gray-800">Admin User</p>
                    <p className="text-gray-500">admin@company.com</p>
                  </div>
                  <button
                    type="button"
                    className="text-xs font-medium text-red-600 hover:text-red-700"
                  >
                    Logout
                  </button>
                </div>
              </div>*/}
            </div>
          </DialogPanel>
        </div>
      </Dialog>

      {/* Desktop Fixed Full-Height Sidebar */}
      <aside className="hidden lg:flex lg:w-64 lg:flex-col lg:fixed lg:inset-y-0 bg-white border-r border-gray-200">
        <div className="flex h-16 items-center px-6 border-b border-gray-200">
          <span className="text-lg font-bold text-gray-900">Invoice Report System</span>
        </div>

        <nav className="flex-1 space-y-1 px-4 py-6">
          {navigation.map((item) => (
            <NavLink key={item.name} to={item.href} className={navLinkClass}>
              {item.name}
            </NavLink>
          ))}
        </nav>

        {/* Desktop Bottom User / Logout Area 
        <div className="border-t border-gray-200 p-4">
          <div className="flex items-center justify-between">
            <div className="text-xs">
              <p className="font-semibold text-gray-800">Admin User</p>
              <p className="text-gray-500">admin@company.com</p>
            </div>
            <button
              type="button"
              className="text-xs font-medium text-red-600 hover:text-red-700">
              Logout
            </button>
          </div>
        </div>*/}
      </aside>

      {/* Main Content Area */}
      <div className="flex flex-1 flex-col min-w-0 lg:pl-64">
        <header className="sticky top-0 z-30 flex h-16 items-center justify-between bg-white border-b border-gray-200 px-4 sm:px-6">
          <div className="flex items-center gap-3 min-w-0">
            <button
              type="button"
              onClick={() => setMobileMenuOpen(true)}
              className="lg:hidden rounded-lg p-2 -ml-2 text-gray-600 hover:bg-gray-100 shrink-0"
            >
              <span className="sr-only">Open sidebar</span>
              <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>

            <h1 className="text-base font-bold text-gray-900 truncate">{pageTitle}</h1>
          </div>

          <div className="flex items-center gap-4 shrink-0">
            <span className="text-xs text-gray-500 hidden sm:inline">
              Welcome back, User
            </span>
          </div>
        </header>

        <main className="flex-1 p-4 sm:p-6 min-w-0">
          <Outlet />
        </main>
      </div>
    </div>
  );
}
