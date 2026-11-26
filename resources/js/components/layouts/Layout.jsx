import React, { useState, useEffect } from 'react';
import { Outlet } from 'react-router-dom';
import { Dialog, DialogBackdrop, DialogPanel } from '@headlessui/react';
import { Bars3Icon, XMarkIcon } from '@heroicons/react/24/outline';
import SideNav from './side-nav.jsx';

export default function Layout({ title = 'Customer Reports' }) {
    const [sidebarOpen, setSidebarOpen] = useState(false);

    // Automatically close mobile sidebar if the screen is resized to desktop (md: 768px)
    useEffect(() => {
        const handleResize = () => {
            if (window.innerWidth >= 768) {
                setSidebarOpen(false);
            }
        };
        window.addEventListener('resize', handleResize);
        return () => window.removeEventListener('resize', handleResize);
    }, []);

    return (
        <div className="min-h-screen flex flex-col bg-gray-100 text-gray-900 overflow-x-hidden">
            
            {/* 1. Mobile Off-Canvas Sidebar (Powered by Headless UI) */}
            <Dialog open={sidebarOpen} onClose={setSidebarOpen} className="relative z-50 md:hidden">
                <DialogBackdrop
                    transition
                    className="fixed inset-0 bg-gray-900/80 transition-opacity duration-300 ease-linear data-[closed]:opacity-0"
                />

                <div className="fixed inset-0 flex">
                    <DialogPanel
                        transition
                        className="relative mr-16 flex w-full max-w-xs flex-1 transition duration-300 ease-in-out data-[closed]:-translate-x-full"
                    >
                        <div className="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4">
                            <div className="flex h-16 shrink-0 items-center justify-between">
                                <span className="font-bold text-lg">Customer Reports</span>
                                <button
                                    type="button"
                                    onClick={() => setSidebarOpen(false)}
                                    className="-m-2.5 p-2.5 text-gray-700"
                                >
                                    <XMarkIcon className="h-6 w-6" aria-hidden="true" />
                                </button>
                            </div>
                            <SideNav onNavigate={() => setSidebarOpen(false)} />
                        </div>
                    </DialogPanel>
                </div>
            </Dialog>

            {/* 2. Static Desktop Sidebar (Fixed w-64, always visible on md+) */}
            <aside className="hidden md:fixed md:inset-y-0 md:z-40 md:flex md:w-64 md:flex-col">
                <div className="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6 pb-4">
                    <div className="flex h-16 shrink-0 items-center">
                        <span className="font-bold text-lg">Customer Reports</span>
                    </div>
                    <SideNav />
                </div>
            </aside>

            {/* 3. Main Content Column */}
            <div className="flex-1 flex flex-col min-w-0 md:pl-64">
                
                {/* Top Navigation Bar */}
                <nav className="sticky top-0 z-30 h-16 bg-white border-b border-gray-200 px-3 sm:px-6 flex items-center justify-between gap-2 shrink-0">
                    <div className="flex items-center gap-2 min-w-0">
                        <button
                            type="button"
                            onClick={() => setSidebarOpen(true)}
                            className="md:hidden p-2 -ml-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none shrink-0"
                            aria-label="Open sidebar"
                        >
                            <Bars3Icon className="w-6 h-6" aria-hidden="true" />
                        </button>

                        <span className="font-bold text-base sm:text-xl text-gray-900 truncate">
                            {title}
                        </span>
                    </div>

                    <span className="hidden sm:inline text-sm text-gray-500 shrink-0">
                        Welcome back
                    </span>
                </nav>

                {/* Page Content */}
                <main className="flex-1 p-3 sm:p-6 min-w-0">
                    <Outlet />
                </main>
            </div>

        </div>
    );
}
