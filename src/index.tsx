import React from 'react';
import { createRoot } from 'react-dom/client';
import './index.css';

function App() {
  return (
    <div className="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-6 text-center">
      <h1 className="text-4xl font-bold text-gray-900 mb-4">WordPress Plugin Built Successfully</h1>
      <p className="text-lg text-gray-600 max-w-2xl">
        The <strong>WP Suspicious PHP Access Scanner</strong> plugin files have been generated in this workspace. 
        You can explore the source code in the left file explorer or use the <strong>Settings &gt; Export</strong> option 
        to download the plugin as a ZIP file.
      </p>
    </div>
  );
}

const container = document.getElementById('root');
if (container) {
  const root = createRoot(container);
  root.render(<App />);
}
