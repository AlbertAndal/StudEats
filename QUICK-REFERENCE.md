# 🚀 StudEats Quick Reference Card

## 📱 Production Access

**Live Application:** https://studeats-13.onrender.com/  
**Admin Dashboard:** https://studeats-13.onrender.com/admin/login

### 🔑 Production Credentials

```
Admin Account:
├─ Email: admin@studeats.com
└─ Password: admin123

Super Admin Account:
├─ Email: superadmin@studeats.com
└─ Password: superadmin123
```

## ⚡ Quick Status Check

```powershell
# Wake up + test the service (Windows PowerShell)
Invoke-WebRequest -Uri "https://studeats-13.onrender.com/" -TimeoutSec 120

# Expected: 200 OK (if service is awake)
# Expected: Timeout or 502 (if service is asleep - wait 60 seconds and retry)
```

## 🕐 Free Tier Behavior

| Event | Response Time | Status Code |
|-------|---------------|-------------|
| **Service Active** | < 1 second | 200 OK |
| **Service Sleeping** | 50-90 seconds | 502 → 200 |
| **After 15 min idle** | Auto spin-down | 💤 |

**Translation:** First visitor after idle period waits ~1 minute. Everyone else gets instant response.

## 🔧 Eliminate Spin-Down (FREE Solution)

**Use UptimeRobot to keep service alive:**

1. **Sign up:** https://uptimerobot.com/ (FREE account)
2. **Add Monitor:**
   - URL: `https://studeats-13.onrender.com/`
   - Type: HTTP(s)
   - Interval: **14 minutes** (important!)
3. **Result:** Service never spins down = No 502 errors

**Alternative:** Upgrade to Render Starter Plan ($7/month) for always-on service

## 📊 Render Dashboard

**Service Management:** https://dashboard.render.com/web/srv-d43uls6mcj7s73bg6qi0

**Quick Actions:**
- View Logs → Real-time application logs
- Manual Deploy → Force redeploy
- Environment → Edit environment variables
- Settings → Change instance type / branch

## 🐛 Common Issues & Solutions

### Issue: 502 Bad Gateway
**Cause:** Service is asleep (Free Tier feature)  
**Fix:** Wait 60 seconds, refresh page  
**Prevention:** Set up UptimeRobot (see above)

### Issue: Admin dashboard not loading
**Verify:**
1. Service is awake (check homepage first)
2. Using correct URL: `/admin/login` not `/admin`
3. Credentials are correct (see above)

### Issue: CSS/JS not loading
**Status:** ✅ Fixed with CDN fallback  
**Verify:** Check browser DevTools → Network tab

## 📈 Application Health Checklist

Run these checks to verify everything works:

```
✅ Homepage loads: https://studeats-13.onrender.com/
✅ Login page: https://studeats-13.onrender.com/login
✅ Admin login: https://studeats-13.onrender.com/admin/login
✅ Assets loading: Check DevTools → Network (200 OK for CSS/JS)
✅ Database: Login successfully = DB connected
```

## 🔄 Deployment Workflow

**Current Branch:** `copilot/vscode1758243505358` (auto-deploys)

**To update production:**
```bash
# Make changes in your code
git add .
git commit -m "Your change description"
git push origin copilot/vscode1758243505358

# Render automatically builds and deploys in ~3-5 minutes
# Watch progress at: https://dashboard.render.com/web/srv-d43uls6mcj7s73bg6qi0
```

**To merge to main branch:**
```bash
git checkout main
git merge copilot/vscode1758243505358
git push origin main

# Then update Render to deploy from 'main' branch:
# Settings → Branch → Change to 'main' → Save
```

## 🎓 For Presentations / Demos

**Wake Up Before Demo:**
```powershell
# Run this 2 minutes before your presentation
Invoke-WebRequest -Uri "https://studeats-13.onrender.com/"
```

**Talking Points:**
- "Deployed on Render cloud platform with PostgreSQL database"
- "Auto-scaling infrastructure (Free Tier spins down when idle)"
- "Continuous deployment from Git repository"
- "Production-ready with error handling and monitoring"

**Backup Plan:**
- Keep localhost running at http://127.0.0.1:8000
- If live site is slow, switch to localhost seamlessly

## 📝 Technical Stack

```
Backend:   Laravel 12.25.0 (PHP 8.2)
Frontend:  Tailwind CSS 4.1.12 + React 19.1.1
Database:  PostgreSQL 16.x (production) / MySQL (local)
Hosting:   Render.com (Docker container)
Build:     Vite 7.x + PostCSS
```

## 🔗 Important Links

| Resource | URL |
|----------|-----|
| **Live App** | https://studeats-13.onrender.com/ |
| **Admin** | https://studeats-13.onrender.com/admin/login |
| **GitHub** | https://github.com/AlbertAndal/StudEats-13 |
| **Render** | https://dashboard.render.com/web/srv-d43uls6mcj7s73bg6qi0 |
| **UptimeRobot** | https://uptimerobot.com/ |

## 🆘 Emergency Contacts

**If something breaks:**
1. Check Render logs (Dashboard → Logs tab)
2. Verify environment variables are set
3. Try manual deploy (Dashboard → Manual Deploy)
4. Check this repo for diagnostic docs:
   - `RENDER-502-FREE-TIER-SPINDOWN.md`
   - `PRODUCTION-500-ERROR-RESOLUTION.md`
   - `500-ERROR-DIAGNOSTIC-REPORT.md`

---

**Current Status:** ✅ Production Ready  
**Last Verified:** November 3, 2025  
**Next Action:** Set up UptimeRobot for 24/7 availability

*Keep this file handy for quick reference!*
