/**
 * Great Endured Technology — Particles Engine
 * Spec from Resource.md (Section 5.1 & 5.3)
 */

export class Particles {
  constructor(canvasId) {
    this.canvas = document.getElementById(canvasId);
    if (!this.canvas) return;

    this.ctx = this.canvas.getContext('2d');
    this.particles = [];
    this.animationFrameId = null;
    this.active = false;

    // Configuration
    this.maxDistance = 120; // Connecting line threshold
    this.particleColor = 'rgba(0, 212, 255, 0.4)';
    this.lineColor = 'rgba(30, 58, 138, 0.15)';
    this.baseSpeed = 0.5;

    // Mouse coordinates (reactive drift)
    this.mouse = { x: null, y: null, radius: 150 };
    this.isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

    this.init();
  }

  init() {
    this.resize();
    this.createParticles();
    this.setupListeners();
    this.start();
  }

  resize() {
    this.width = this.canvas.width = this.canvas.parentElement.clientWidth;
    this.height = this.canvas.height = this.canvas.parentElement.clientHeight;

    // Capped count based on device width (Resource.md 5.3)
    if (window.innerWidth < 768) {
      this.particleCount = 35; // Mobile limit
    } else {
      this.particleCount = 90; // Desktop limit
    }
  }

  createParticles() {
    this.particles = [];
    for (let i = 0; i < this.particleCount; i++) {
      this.particles.push({
        x: Math.random() * this.width,
        y: Math.random() * this.height,
        vx: (Math.random() - 0.5) * this.baseSpeed,
        vy: (Math.random() - 0.5) * this.baseSpeed,
        size: Math.random() * 2 + 1
      });
    }
  }

  setupListeners() {
    window.addEventListener('resize', () => {
      const oldWidth = this.width;
      this.resize();
      if (Math.abs(oldWidth - this.width) > 50) {
        this.createParticles();
      }
    });

    if (!this.isTouch) {
      window.addEventListener('mousemove', (e) => {
        const rect = this.canvas.getBoundingClientRect();
        this.mouse.x = e.clientX - rect.left;
        this.mouse.y = e.clientY - rect.top;
      });

      window.addEventListener('mouseleave', () => {
        this.mouse.x = null;
        this.mouse.y = null;
      });
    }

    // Visibility Observer to pause loop when tab is backgrounded
    document.addEventListener('visibilitychange', () => {
      if (document.visibilityState === 'visible') {
        this.start();
      } else {
        this.stop();
      }
    });

    // IntersectionObserver to pause loop when canvas is off-screen
    if ('IntersectionObserver' in window) {
      const io = new IntersectionObserver((entries) => {
        entries.forEach(e => {
          if (e.isIntersecting) {
            this.start();
          } else {
            this.stop();
          }
        });
      }, { threshold: 0.05 });
      io.observe(this.canvas);
    }
  }

  draw() {
    this.ctx.clearRect(0, 0, this.width, this.height);

    // Render & update particles
    for (let i = 0; i < this.particles.length; i++) {
      const p = this.particles[i];

      // Standard movement
      p.x += p.vx;
      p.y += p.vy;

      // Wrap-around borders
      if (p.x < 0) p.x = this.width;
      if (p.x > this.width) p.x = 0;
      if (p.y < 0) p.y = this.height;
      if (p.y > this.height) p.y = 0;

      // Mouse interactive drift (repulsion/attraction)
      if (this.mouse.x !== null && this.mouse.y !== null) {
        const dx = p.x - this.mouse.x;
        const dy = p.y - this.mouse.y;
        const dist = Math.sqrt(dx * dx + dy * dy);
        if (dist < this.mouse.radius) {
          const force = (this.mouse.radius - dist) / this.mouse.radius;
          p.x += (dx / dist) * force * 1.5;
          p.y += (dy / dist) * force * 1.5;
        }
      }

      // Draw particle (Resource.md: animate transform/opacity, keep drawing simple)
      this.ctx.beginPath();
      this.ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
      this.ctx.fillStyle = this.particleColor;
      this.ctx.fill();

      // Connect lines
      for (let j = i + 1; j < this.particles.length; j++) {
        const p2 = this.particles[j];
        const dx = p.x - p2.x;
        const dy = p.y - p2.y;
        const dist = Math.sqrt(dx * dx + dy * dy);

        if (dist < this.maxDistance) {
          // Fade opacity based on distance
          const alpha = (1 - dist / this.maxDistance) * 0.15;
          this.ctx.beginPath();
          this.ctx.moveTo(p.x, p.y);
          this.ctx.lineTo(p2.x, p2.y);
          this.ctx.strokeStyle = `rgba(0, 212, 255, ${alpha})`;
          this.ctx.lineWidth = 0.5;
          this.ctx.stroke();
        }
      }
    }

    if (this.active) {
      this.animationFrameId = requestAnimationFrame(() => this.draw());
    }
  }

  start() {
    if (!this.active) {
      this.active = true;
      this.draw();
    }
  }

  stop() {
    this.active = false;
    if (this.animationFrameId) {
      cancelAnimationFrame(this.animationFrameId);
      this.animationFrameId = null;
    }
  }
}
export default Particles;
