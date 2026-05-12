import { NextResponse } from 'next/server';
import fs from 'fs/promises';
import path from 'path';

const settingsPath = path.join(process.cwd(), 'src/data/settings.json');

export async function GET() {
  try {
    const data = await fs.readFile(settingsPath, 'utf8');
    return NextResponse.json(JSON.parse(data));
  } catch (error) {
    return NextResponse.json({ error: 'Failed to read settings' }, { status: 500 });
  }
}

export async function POST(request: Request) {
  try {
    const newSettings = await request.json();
    
    // Basic validation
    if (!newSettings.whatsappNumber || !newSettings.whatsappDisplay) {
      return NextResponse.json({ error: 'Invalid data' }, { status: 400 });
    }

    await fs.writeFile(settingsPath, JSON.stringify(newSettings, null, 2));
    
    return NextResponse.json({ success: true });
  } catch (error) {
    return NextResponse.json({ error: 'Failed to save settings' }, { status: 500 });
  }
}
