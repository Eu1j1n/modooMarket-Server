import java.io.IOException;
import java.io.PrintWriter;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.ArrayList;

public class MyServer {
    public static ArrayList<PrintWriter> m_OutputList;

    public static void main(String[] args){
        m_OutputList = new ArrayList<PrintWriter>();

        try{
            ServerSocket s_socket = new ServerSocket(8888);
            System.out.println("서버가 시작되었습니다. 포트 8888에서 대기 중...");
            while(true){
                Socket c_socket = s_socket.accept(); //클라이언트 접속 대기
                System.out.println("새로운 클라이언트가 연결되었습니다.");
                ClientManagerThread c_thread = new ClientManagerThread();
                c_thread.setSocket(c_socket);

                m_OutputList.add(new PrintWriter(c_socket.getOutputStream()));
                System.out.println(m_OutputList.size());
                System.out.println("현재 연결된 클라이언트 수: " + m_OutputList.size());
                c_thread.start();
            }
        }catch(IOException e){
            e.printStackTrace();
        }
    }
}