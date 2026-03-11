<style>
	.quotation-preview { --accent: #c9a227; --accent-dark: #9a7b1a; --bg-dark: #1a1d21; --bg-card: #24282e; --text-muted: #8b9298; }
	.quotation-preview .hero-gradient { background: linear-gradient(135deg, var(--bg-dark) 0%, #2d3238 50%, var(--bg-dark) 100%); min-height: 280px; position: relative; overflow: hidden; }
	.quotation-preview .hero-gradient::before { content: ''; position: absolute; top: -50%; right: -20%; width: 60%; height: 200%; background: radial-gradient(ellipse, rgba(201,162,39,0.15) 0%, transparent 70%); pointer-events: none; }
	.quotation-preview .company-badge { background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%); color: #1a1d21; padding: 0.5rem 1.25rem; border-radius: 4px; font-weight: 700; font-size: 1.1rem; letter-spacing: 0.05em; display: inline-block; margin-bottom: 1rem; box-shadow: 0 4px 15px rgba(201,162,39,0.3); }
	.quotation-preview .doc-badge { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.15); padding: 0.75rem 1.5rem; border-radius: 8px; font-size: 1.5rem; font-weight: 700; color: #fff; }
	.quotation-preview .content-wrap { margin-top: -120px; position: relative; z-index: 2; }
	.quotation-preview .info-card { background: var(--bg-card); border-radius: 12px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.06); box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
	.quotation-preview .info-card h3 { color: var(--accent); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.5rem; }
	.quotation-preview .table-card { background: var(--bg-card); border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.06); box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
	.quotation-preview .table-card table { width: 100%; border-collapse: collapse; }
	.quotation-preview .table-card thead { background: rgba(201,162,39,0.15); }
	.quotation-preview .table-card th { padding: 1rem 1.25rem; text-align: left; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--accent); font-weight: 600; }
	.quotation-preview .table-card td { padding: 1rem 1.25rem; border-top: 1px solid rgba(255,255,255,0.05); color: #e4e6eb; }
	.quotation-preview .table-card tbody tr:hover { background: rgba(255,255,255,0.02); }
	.quotation-preview .product-thumb { width: 56px; height: 56px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); }
	.quotation-preview .product-code { color: var(--accent); font-weight: 600; margin-right: 0.5rem; }
	.quotation-preview .text-gold { color: var(--accent) !important; }
	.quotation-preview .footer-note { color: var(--text-muted); font-size: 0.8rem; margin-top: 2rem; padding: 1rem; background: rgba(0,0,0,0.2); border-radius: 8px; }
</style>
