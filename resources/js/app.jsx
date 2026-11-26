import './bootstrap';

import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';

import SideNavLayout from './components/layouts/side-nav.jsx';
import GenerateReports from './pages/GenerateReports.jsx';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route element={<SideNavLayout />}>
          <Route path="/" element={<GenerateReports />} />
          <Route path="/generate-reports" element={<GenerateReports />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

ReactDOM.createRoot(document.getElementById('app')).render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);
