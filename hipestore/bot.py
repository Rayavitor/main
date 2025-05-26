import discord
from discord.ext import commands
import mercadopago
import qrcode
import io

intents = discord.Intents.default()
intents.message_content = True
bot = commands.Bot(command_prefix="!", intents=intents)

mp = mercadopago.SDK("APP_USR-2016932607138605-052508-42bd128ea282dc181e91d1571ac039fb-717395386")

@bot.command()
async def comprar(ctx, valor: float):
    user_id = str(ctx.author.id)

    payment_data = {
        "transaction_amount": valor,
        "description": f"Compra Discord User {user_id}",
        "payment_method_id": "pix",
        "payer": {
            "email": "seu-email@exemplo.com"  # pode ser qualquer email aqui, ou melhor pegar do usuário se tiver
        }
    }

    payment_response = mp.payment().create(payment_data)
    payment = payment_response["response"]

    qr_code_base64 = payment.get("point_of_interaction", {}).get("transaction_data", {}).get("qr_code_base64")
    qr_code = payment.get("point_of_interaction", {}).get("transaction_data", {}).get("qr_code")

    if not qr_code_base64 or not qr_code:
        await ctx.send("Não foi possível gerar o QR Code de pagamento.")
        return

    img = qrcode.make(qr_code)
    img_buffer = io.BytesIO()
    img.save(img_buffer, format='PNG')
    img_buffer.seek(0)

    file = discord.File(fp=img_buffer, filename="qrcode.png")
    await ctx.send(f"{ctx.author.mention}, pague R${valor:.2f} via PIX usando o QR Code abaixo:", file=file)
    await ctx.send(f"Caso não consiga escanear, use este código PIX:\n```\n{qr_code}\n```")

bot.run("MTIxMzUwOTA0NTA1OTcyMzI3NQ.GbXSty.EznteVhvfXm3R704lXCCwH8bBPpkVUPiadqdEM")